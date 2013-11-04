<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This is the object that represents a merchant. You can either point
 *	instructions to this class directly, or you can use the STAPI class
 *	to delegate them.
 *
 *	Merchant information is usually not required when using STPP, however
 *	it is a required feature for 3D-Secure, which most if not all
 *	transactions should be performed using (if available).
 *	
 *	@version: untested
 *	@author: David Weston <stpp@typefish.co.uk>
 */


class STPPMerchant extends STPPObject
{
	/**
	 *	Set the name of the merchant.
	 */
	public function setName($name)
	{
		$this->options["name"] = $name;
		return $this;
	}
	
	
	/**
	 *	Set the e-mail address of the merchant.
	 */
	public function setEmail($email)
	{
		$this->options["email"] = $email;
		return $this;
	}
	
	
	/**
	 *	Set the merchant-facing order reference of this order.
	 */
	public function setOrderReference($order)
	{
		$this->options["orderreference"] = $order;
		return $this;
	}
	
	
	/**
	 *	Set the terminating URL - for use in 3DSecure transactions.
	 */
	public function set3DSecureTermUrl($url)
	{
		$this->options["termurl"] = $url;
		return $this;
	}
}