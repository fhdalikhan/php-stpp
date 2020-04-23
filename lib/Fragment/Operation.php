<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	The operation object contains some other information that is needed
 *	to complete the request.
 *	
 *	@version: 2.0.0
 *	@author: David Weston <westie@typefish.co.uk>
 */


namespace OUTRAGElib\Payment\STPP\Fragment;


class Operation extends FragmentAbstract
{
	/**
	 *	Set the account type description of the call. Would normally
	 *	default to ECOM, you wouldn't usually need to vary from this.
	 */
	public function setAccountTypeDescription($type)
	{
		$this->options["accounttypedescription"] = $type;
		return $this;
	}
	
	
	/**
	 *	Set the parent transaction reference.
	 */
	public function setParentTransactionReference($reference)
	{
		$this->options["parenttransactionreference"] = $reference;
		return $this;
	}

	/**
	 *	Set the subscriptionnumber.
	 */
	public function setSubscriptionNumber($number)
	{
		$this->options["subscriptionnumber"] = $number;
		return $this;
	}
	
	/**
	 *	Set the subscriptiontype.
	 */
	public function setSubscriptionType($type)
	{
		$this->options["subscriptiontype"] = $type;
		return $this;
	}
	
	/**
	 *	Set the credentialsonfile 1 - store, 2 - use stored, not set - do not store
	 */
	public function setCredentialsOnFile($number)
	{
		$this->options["credentialsonfile"] = $number;
		return $this;
	}
	
	/**
	 *	Set the site reference that is being used in this operation.
	 */
	public function setSiteReference($reference)
	{
		$this->options["sitereference"] = $reference;
		return $this;
	}
	
	
	/**
	 *	Set the 3DSecure MD parameter. Based on values sent back from ST.
	 */
	public function set3DSecureMD($value)
	{
		$this->options["md"] = $value;
		return $this;
	}
	
	
	/**
	 *	Set the 3DSecure PaReq (payer authentication request) value.
	 *	Based on values sent back from ST.
	 */
	public function set3DSecurePaReq($value)
	{
		$this->options["pareq"] = $value;
		return $this;
	}
	
	
	/**
	 *	Set the 3DSecure PaRes (payer authentication response) value.
	 *	Based on values sent back from ST.
	 */
	public function set3DSecurePaRes($value)
	{
		$this->options["pares"] = $value;
		return $this;
	}
}
