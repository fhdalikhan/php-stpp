<?php
/**
 *	Request for STPP/STAPI
 *
 *	@version: 2.0.0
 *	@author: David Weston <stpp@typefish.co.uk>
 */


namespace OUTRAGElib\Payment\STPP;

use \OUTRAGElib\Payment\STPP\Fragment\Billing;
use \OUTRAGElib\Payment\STPP\Fragment\Customer;
use \OUTRAGElib\Payment\STPP\Fragment\Merchant;
use \OUTRAGElib\Payment\STPP\Fragment\Operation;
use \OUTRAGElib\Payment\STPP\Fragment\Settlement;
use \OUTRAGElib\Payment\STPP\Response;
use \SimpleXMLElement;


class Request
{
	/**
	 *	Alias information.
	 */
	protected $alias = null;
	
	
	/**
	 *	Stores each of the objects used in this request.
	 */
	protected $objects = [];
	
	
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
	public function setBilling(Billing $billing)
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
			$this->objects["billing"] = new Billing();
		
		return $this->objects["billing"];
	}
	
	
	/**
	 *	Sets the customer object - you can either separately supply an object
	 *	that represents a customer, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setCustomer(Customer $customer)
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
			$this->objects["customer"] = new Customer();
		
		return $this->objects["customer"];
	}
	
	
	/**
	 *	Sets the merchant object - you can either separately supply an object
	 *	that represents a merchant, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setMerchant(Merchant $merchant)
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
			$this->objects["merchant"] = new Merchant();
		
		return $this->objects["merchant"];
	}
	
	
	/**
	 *	Sets the operation object - you can either separately supply an object
	 *	that represents an operation, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setOperation(Operation $operation)
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
			$this->objects["operation"] = new Operation();
		
		return $this->objects["operation"];
	}
	
	
	/**
	 *	Sets the settlement object - you can either separately supply an object
	 *	that represents a settlement, or you can use the simulated methods which
	 *	will do pretty much the same thing.
	 */
	public function setSettlement(Settlement $settlement)
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
			$this->objects["settlement"] = new Settlement();
		
		return $this->objects["settlement"];
	}
	
	
	/**
	 *	Called when compiling all of the nodes required for this request to properly proceed.
	 *	Nothing like inter-breeding SimpleXML and DOM!
	 */
	public function getRequestXML($method)
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
		
		return $envelope->asXML();
	}
}