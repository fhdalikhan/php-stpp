<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This is the object that deals with billing, such as who owns
 *	the card and the like. It's recommended that everything stuck into
 *	this class matches the name registered on the card. Addresses
 *	are also key.
 *
 *	@version: untested
 *	@author: David Weston <stpp@typefish.co.uk>
 */


class STPPBilling extends STPPAddressable
{
	/**
	 *	Store all of the options this object holds in here.
	 */
	protected $options = array();
	
	
	/**
	 *	Set the amount that this transaction is for. If not already defined,
	 *	the currency shall be in GBP. Decimal units are accepted here, and will
	 *	be automatically translated into a integer pence value.
	 */
	public function setAmount($amount)
	{
		if(!isset($this->options["amount"]))
			$this->options["amount"] = array();
		
		if(empty($this->options["amount"]["currencycode"]))
			$this->options["amount"]["currencycode"] = "GBP";
		
		$this->options["amount"]["value"] = (integer) ($amount * 100);
		
		return $this;
	}
	
	
	/**
	 *	Set the currency that this transaction shall be performed in.
	 */
	public function setCurrency($currency)
	{
		if(!isset($this->options["amount"]))
			$this->options["amount"] = array();
		
		$this->options["amount"]["currencycode"] = $currency;
		
		return $this;
	}
	
	
	/**
	 *	Set the payment card type - something like VISA or MASTERCARD. Values will
	 *	always be translated into uppercase.
	 */
	public function setPaymentType($type)
	{
		if(!isset($this->options["payment"]))
			$this->options["payment"] = array();
		
		$this->options["payment"]["type"] = strtoupper($type);
		
		return $this;
	}
	
	
	/**
	 *	Set the credit or debit card number.
	 */
	public function setPaymentCardNumber($number)
	{
		if(!isset($this->options["payment"]))
			$this->options["payment"] = array();
		
		$set = array
		(
			" ",
			"-",
		);
		
		$this->options["payment"]["pan"] = str_replace($set, "", $number);
		
		return $this;
	}
	
	
	/**
	 *	Set the registered expiry date of the payment method that is being used.
	 *
	 *	Quite a few formats are accepted:
	 *		- MM/YYYY							-> default format
	 *		- [ MM, YY or YYYY ]
	 *		- { month: MM, year: YY or YYYY }
	 */
	public function setPaymentExpiryDate($expirydate)
	{
		if(!isset($this->options["payment"]))
			$this->options["payment"] = array();
		
		if(is_array($expirydate))
		{
			$month = null;
			$year = null;
			
			if(isset($expirydate["month"]))
			{
				$month = $expirydate["month"];
				$year = $expirydate["year"];
			}
			else
			{
				$month = $expirydate[0];
				$year = $expirydate[1];
			}
			
			$month = str_pad($month, 2, "0", STR_PAD_LEFT);
			
			if($year < 100)
				$year = 2000 + $year;
			
			$expirydate = $month."/".$year;
		}
		elseif(is_numeric($expirydate) || is_int($expirydate))
		{
			$expirydate = date("m/Y", $expirydate);
		}
		
		$this->options["payment"]["expirydate"] = (string) $expirydate;
		
		return $this;
	}
	
	
	/**
	 *	Set the security code of the card that is being used in this transaction.
	 */
	public function setPaymentSecurityCode($code)
	{
		if(!isset($this->options["payment"]))
			$this->options["payment"] = array();
		
		$this->options["payment"]["securitycode"] = $code;
		
		return $this;
	}
	
	
	/**
	 *	Compiles the amount values.
	 */
	protected function compileAmount($element)
	{
		if(empty($this->options["amount"]))
			return false;
		
		$element->addChild("amount", $this->options["amount"]["value"])->addAttribute("currencycode", $this->options["amount"]["currencycode"]);
		
		return true;
	}
	
	
	/**
	 *	Compiles the payment information.
	 */
	protected function compilePayment($element)
	{
		if(empty($this->options["payment"]))
			return false;
		
		ksort($this->options["payment"]);
		
		$node = $element->addChild("payment");
		
		if(isset($this->options["payment"]["type"]))
			$node->addAttribute("type", $this->options["payment"]["type"]);
		
		unset($this->options["payment"]["type"]);
		
		foreach($this->options["payment"] as $option => $value)
			$node->addChild($option, $value);
		
		return true;
	}
}