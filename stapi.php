<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This won't allow you to connect your system immediately
 *	to a setup, however, it'll allow you to with ease create
 *	and maintain a new contract with a SecureTrading node.
 *	
 *	@version: untested
 *	@author: David Weston <stpp@typefish.co.uk>
 */


/**
 *	Okay, so it's probably a good thing to include quite a lot of
 *	classes that we need for this object to work properly.
 */
require "objects/stppobject.php";
require "objects/stppaddressableobject.php";

require "objects/stppbilling.php";
require "objects/stppcustomer.php";
require "objects/stppmerchant.php";
require "objects/stppoperation.php";
require "objects/stppsettlement.php";

require "objects/stppresponse.php";


/**
 *	Actual STAPI object
 */
class STAPI
{
	/**
	 *	Details about the connection to our local endpoint.
	 */
	protected $alias = null;
	protected $connection = null;
	
	
	/**
	 *	Stores each of the objects used in this request.
	 */
	protected $objects = array();
	
	
	/**
	 *	Called whenever we want to start talking with SecureTrading.
	 */
	public function __construct($address = "127.0.0.1", $port = 5000)
	{
		if($address != null)
			$this->connect($address, $port);
	}
	
	
	/**
	 *	A destructor, destructing things.
	 */
	public function __destruct()
	{
		$this->disconnect();
	}
	
	
	/**
	 *	Begin a connection to ST.
	 */
	protected function connect($address, $port)
	{
		$errno = null;
		$errstr = null;
		
		if($this->connection = @fsockopen($address, $port, $errno, $errstr))
			return true;
		
		return false;
	}
	
	
	/**
	 *	Kills our connection to ST.
	 */
	protected function disconnect()
	{
		if($this->connection)
			return fclose($this->connection);
		
		return true;
	}
	
	
	/**
	 *	Set the alias of the current request.
	 */
	public function setAlias($alias)
	{
		$this->alias = $alias;
		return $this;
	}
	
	
	/**
	 *	Sets the billing object - you can either separately supply an object
	 *	that represents a billing, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setBilling($billing)
	{
		$this->objects["billing"] = $billing;
		return $this;
	}
	
	
	/**
	 *	Retrieves the object that is used to represent the billing.
	 */
	public function getBilling()
	{
		if(!isset($this->objects["billing"]))
			$this->resetBilling();
		
		return $this->objects["billing"];
	}
	
	
	/**
	 *	Clears the merchant to a blank state.
	 */
	public function resetBilling()
	{
		$this->objects["billing"] = new STPPBilling();
		return $this;
	}
	
	
	/**
	 *	Sets the customer object - you can either separately supply an object
	 *	that represents a customer, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setCustomer($customer)
	{
		$this->objects["customer"] = $customer;
		return $this;
	}
	
	
	/**
	 *	Retrieves the object that is used to represent the customer.
	 */
	public function getCustomer()
	{
		if(!isset($this->objects["customer"]))
			$this->resetCustomer();
		
		return $this->objects["customer"];
	}
	
	
	/**
	 *	Clears the merchant to a blank state.
	 */
	public function resetCustomer()
	{
		$this->objects["customer"] = new STPPCustomer();
		return $this;
	}
	
	
	/**
	 *	Sets the merchant object - you can either separately supply an object
	 *	that represents a merchant, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setMerchant($merchant)
	{
		$this->objects["merchant"] = $merchant;
		return $this;
	}
	
	
	/**
	 *	Retrieves the object that is used to represent the merchant.
	 */
	public function getMerchant()
	{
		if(!isset($this->objects["merchant"]))
			$this->resetMerchant();
		
		return $this->objects["merchant"];
	}
	
	
	/**
	 *	Clears the merchant to a blank state.
	 */
	public function resetMerchant()
	{
		$this->objects["merchant"] = new STPPMerchant();
		return $this;
	}
	
	
	/**
	 *	Sets the operation object - you can either separately supply an object
	 *	that represents an operation, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setOperation($operation)
	{
		$this->objects["operation"] = $operation;
		return $this;
	}
	
	
	/**
	 *	Retrieves the object that is used to represent the operation.
	 */
	public function getOperation()
	{
		if(!isset($this->objects["operation"]))
			$this->resetOperation();
		
		return $this->objects["operation"];
	}
	
	
	/**
	 *	Clears the operation to a blank state.
	 */
	public function resetOperation()
	{
		$this->objects["operation"] = new STPPOperation();
		return $this;
	}
	
	
	/**
	 *	Sets the settlement object - you can either separately supply an object
	 *	that represents a settlement, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setSettlement($settlement)
	{
		$this->objects["settlement"] = $settlement;
		return $this;
	}
	
	
	/**
	 *	Retrieves the object that is used to represent the settlement.
	 */
	public function getSettlement()
	{
		if(!isset($this->objects["settlement"]))
			$this->resetSettlement();
		
		return $this->objects["settlement"];
	}
	
	
	/**
	 *	Clears the settlement to a blank state.
	 */
	public function resetSettlement()
	{
		$this->objects["settlement"] = new STPtSettlement();
		return $this;
	}
	
	
	/**
	 *	Some fancy __call abuse - we'll manhandle the supplied function name, then
	 *	try to figure out what component it needs to call, then call it. Simple!
	 *
	 *	If the result call returns an instance of STPPObject (IE: something like billing)
	 *	then /this/ class will be returned instead, to maintain the similiarities in
	 *	calling conventions.
	 *
	 *	Otherwise, the correct value will be returned unmolested.
	 *
	 *	@todo: Fix this so that stuff like setOperation3DSecure... works.
	 */
	public function __call($method, $arguments)
	{
		$set = array();
		
		preg_match_all("/((?:^|[A-Z])[a-z]+)/", $method, $set);
		
		$set = $set[0];
		$caller = "get".$set[1];
		
		unset($set[1]);
		
		$method = implode("", $set);
		
		if(!method_exists($this, $caller))
			return null;
		
		$callback = array
		(
			$this->$caller(),
			$method,
		);
		
		if(!method_exists($callback[0], $callback[1]))
			return null;
		
		$result = call_user_func_array($callback, $arguments);
		
		if($result instanceof STPPObject)
			return $this;
		
		return $result;
	}
	
	
	/**
	 *	Some __get abuse - if there is an object getter, call it and 
	 *	return its value.
	 */
	public function __get($property)
	{
		$caller = "get".ucfirst($property);
		
		if(!method_exists($this, $caller))
			return null;
		
		return $this->$caller();
	}
	
	
	/**
	 *	Used to push a request off to the SecureTrading endpoint.
	 */
	public function call($type)
	{
		$failure = new STPPResponse("<responseblock></responseblock>");
		
		if(!$this->connection)
			return $failure;
		
		$request = $this->compile($type);
		$outbound = $request->asXML()."\r\n";
		
		if(!fwrite($this->connection, $outbound, strlen($outbound)))
			return $failure;
		
		$response = "";
		
		while(($chunk = fread($this->connection, 4096)) != false)
			$response .= $chunk;
		
		if(!$response)
			return $failure;
		
		return new STPPResponse($response, $request);
	}
	
	
	/**
	 *	Called when compiling all of the nodes required for this request to properly proceed.
	 *	Nothing like inter-breeding SimpleXML and DOM!
	 */
	public function compile($method)
	{
		$envelope = new SimpleXMLElement("<?xml version='1.0' encoding='UTF-8'?><requestblock></requestblock>");
		
		$envelope->addAttribute("version", "3.67");
		$envelope->addChild("alias", $this->alias);
		
		$method = strtoupper($method);
		
		$request = $envelope->addChild("request");
		$request->addAttribute("type", $method);
		
		ksort($this->objects);
		
		$document = dom_import_simplexml($request);
		
		foreach($this->objects as $object => $node)
		{
			$element = new SimpleXMLElement("<".$object."></".$object.">");
			
			if(!$node->compile($element))
				continue;
			
			$component = dom_import_simplexml($element);
			$component = $document->ownerDocument->importNode($component, true);
			
			$document->appendChild($component);
		}
		
		return $envelope;
	}
}