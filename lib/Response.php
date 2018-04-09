<?php
/**
 *	Response for STPP/STAPI
 *
 *	@version: 2.0.0
 *	@author: David Weston <stpp@typefish.co.uk>
 */


namespace OUTRAGElib\Payment\STPP;

use \OUTRAGElib\Payment\STPP\Request;
use \SimpleXMLElement;


class Response
{
	/**
	 *	Store the XML response somewhere...
	 */
	private $feed = null;
	
	
	/**
	 *	The request?
	 */
	private $request = null;
	
	
	/**
	 *	Called when the response has been constructed.
	 */
	public function __construct(Request $request, $response)
	{
		$this->feed = simplexml_load_string($response);
		$this->request = $request;
		
		return true;
	}
	
	
	/**
	 *	Retrieves a cleansed version of the request - where this means
	 *	all credit card information is removed.
	 */
	public function getRequest()
	{
		return $this->request;
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
		if(!isset($this->feed->response->error->code))
			return null;
		
		return ((integer) $this->feed->response->error->code == 0);
	}
	
	
	/**
	 *	Retrieves the error message.
	 */
	public function getErrorMessage()
	{
		if($this->isSuccessful())
			return null;
		
		return (array) $this->feed->response->error;
	}
	
	
	/**
	 *	Retrieves the transaction reference that applies to this
	 *	transaction.
	 */
	public function getTransactionReference()
	{
		if(!isset($this->feed->response->transactionreference))
			return null;
		
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
			return [];
		
		$set = [];
		
		foreach($this->feed->response->security as $node)
		{
			switch((integer) $node)
			{
				case 0:
				case 1:
					$set[$node->getName()] = null;
				break;
				
				case 2:
					$set[$node->getName()] = true;
				break;
				
				case 4:
					$set[$node->getName()] = false;
				break;
			}
		}
		
		return $set;
	}
	
	
	/**
	 *	Check if the response indicated that this request/response pair
	 *	actually is associated with the testing environment or not.
	 */
	public function isLiveEnvironment()
	{
		if(!isset($this->feed->response->live))
			return null;
		
		return (integer) $this->feed->response->live === 1;
	}
}