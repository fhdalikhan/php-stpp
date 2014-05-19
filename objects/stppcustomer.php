<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This is the object that represents a customer. You can either point
 *	instructions to this class directly, or you can use the STAPI class
 *	to delegate them.
 *
 *	Customer information does not constitute billing information, so there's
 *	no real need to fill in anything like addresses and such - however there
 *	/is/ a recommendation from ST to do so.
 *	
 *	@version: 1.0
 *	@author: David Weston <stpp@typefish.co.uk>
 */


class STPPCustomer extends STPPAddressable
{
	/**
	 *	Store all of the options this object holds in here.
	 */
	protected $options = array();
	
	
	/**
	 *	Set the e-mail address of the customer.
	 */
	public function setEmail($email)
	{
		$this->options["email"] = $email;
		return $this;
	}
	
	
	/**
	 *	Set the IP that has been used to set up the request.
	 */
	public function setIP($address)
	{
		$this->options["forwardedip"] = $address;
		return $this;
	}
	
	
	/**
	 *	Set the IP address that has been proxied across during this request.
	 */
	public function setForwardedIP($address)
	{
		$this->options["forwardedip"] = $address;
		return $this;
	}
	
	
	/**
	 *	Set the user agent's Accept header - for use only with 3D secure.
	 */
	public function setAcceptHeader($accept)
	{
		$this->options["accept"] = $accept;
		return $this;
	}
	
	
	/**
	 *	Set the user agent - for use only with 3D secure.
	 */
	public function setUserAgent($accept)
	{
		$this->options["useragent"] = $accept;
		return $this;
	}
}