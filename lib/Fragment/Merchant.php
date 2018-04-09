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
 *	@version: 2.0.0
 *	@author: David Weston <westie@typefish.co.uk>
 */


namespace OUTRAGElib\Payment\STPP\Fragment;


class Merchant extends FragmentAbstract
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
	public function setTermUrl($url)
	{
		$this->options["termurl"] = $url;
		return $this;
	}
}