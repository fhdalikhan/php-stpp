<?php
/**
 *	Response for STPP/STAPI
 *
 *	@version: 2.0.0
 *	@author: David Weston <stpp@typefish.co.uk>
 */


namespace OUTRAGElib\Payment\STPP;

use \OUTRAGElib\Payment\STPP\Request;
use \OUTRAGElib\Payment\STPP\Response;


class RequestHandler
{
	/**
	 *	HTTPS Request information
	 */
	protected $endpoint = null;
	protected $username = null;
	protected $password = null;
	
	
	/**
	 *	Request object
	 */
	protected $request = null;
	
	
	/**
	 *	Set endpoint
	 */
	public function setEndpoint($endpoint)
	{
		$this->endpoint = $endpoint;
		return $this;
	}
	
	
	/**
	 *	Set username
	 */
	public function setUsername($username)
	{
		$this->username = $username;
		return $this;
	}
	
	
	/**
	 *	Set password
	 */
	public function setPassword($password)
	{
		$this->password = $password;
		return $this;
	}
	
	
	/**
	 *	Set request
	 */
	public function setRequest(Request $request)
	{
		$this->request = $request;
		return $this;
	}
	
	
	/**
	 *	Used to push a request off to the SecureTrading endpoint.
	 */
	public function call($method)
	{
		$ch = curl_init();
		
		$headers = [
			"Content-Type: text/xml",
			"Accept: text/xml",
			"Authorization: Basic ".base64_encode($this->username.":".$this->password),
		];
		
		curl_setopt($ch, CURLOPT_URL, $this->endpoint);
		curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		curl_setopt($ch, CURLOPT_HEADER, false);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $this->request->getRequestXML($method));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		
		$output = curl_exec($ch);
		$status = curl_getinfo($ch, CURLINFO_HTTP_CODE) == 200;
		
		curl_close($ch);
		
		return new Response($output);
	}
}