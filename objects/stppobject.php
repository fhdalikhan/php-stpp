<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This is the god object for all parts of the request, such as
 *	the merchant, operations and such.
 *	
 *	@version: untested
 *	@author: David Weston <stpp@typefish.co.uk>
 */


abstract class STPPObject
{
	/**
	 *	Store all of the options this object holds in here.
	 */
	protected $options = array();
	
	
	/**
	 *	Deals with compiling an XML node with which we can use in
	 *	fulfilling a request to the endpoint.
	 */
	public function compile($element)
	{
		if(!$this->options)
			return false;
		
		ksort($this->options);
		
		foreach($this->options as $option => $value)
		{
			$method = "compile".$option;
			
			if(method_exists($this, $method))
				$this->$method($element);
			else
				$element->addChild(strtolower($option), $value);
		}
		
		return true;
	}
	
	
	/**
	 *	Retrieves the options defined using the API.
	 */
	public final function getOptions()
	{
		return $this->options;
	}
}