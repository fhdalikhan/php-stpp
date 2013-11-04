<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	The STPPResponse object parses the response from the SecureTrading endpoint,
 *	and puts it into a nice easy to use output.
 *
 *	@version: untested
 *	@author: David Weston <stpp@typefish.co.uk>
 */


class STPPResponse
{
	/**
	 *	Store the XML response somewhere...
	 */
	protected $feed = null;
	
	
	/**
	 *	Called when the response has been constructed.
	 */
	public function __construct($response)
	{
		$this->feed = simplexml_load_string($response);
		return true;
	}
	
	
	/**
	 *	Retrieves the XML object that was sent back from SecureTrading.
	 */
	public function getResponse()
	{
		return $this->feed;
	}
	
	
	/**
	 *	Check if the request that was sent was successful.
	 */
	public function isSuccessful()
	{
		if(!isset($this->feed->error->code))
			return null;
		
		return ((integer) $this->feed->error->code == 0);
	}
	
	
	/**
	 *	Retrieves the transaction reference that applies to this
	 *	transaction.
	 */
	public function getTransactionReference()
	{
		return (string) $this->feed->response->transactionreference;
	}
	
	
	/**
	 *	If provided in the response, retrieves the security hints given in the response
	 *	and parses them into easier to understand/evaluate types.
	 *
	 *	(null)  -> unable to verify
	 *	(false) -> not valid
	 *	(true)  -> valid
	 */
	public function getSecurityRating()
	{
		if(!isset($this->feed->response->security))
			return array();
		
		$set = array();
		
		foreach($this->feed->response->security as $node)
		{
			switch((integer) $node)
			{
				case 0:
				case 1:
				{
					$set[$node->getName()] = null;
					break;
				}
				
				case 2:
				{
					$set[$node->getName()] = true;
					break;
				}
				
				case 4:
				{
					$set[$node->getName()] = false;
					break;
				}
			}
		}
		
		return $set;
	}
}