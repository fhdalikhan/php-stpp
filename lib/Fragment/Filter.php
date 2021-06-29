<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This is the object that represents a filter.
 *
 *	
 *	@version: 2.0.0
 *	@author: Tomek J
 */


namespace OUTRAGElib\Payment\STPP\Fragment;


class Filter extends FragmentAbstract
{
	
	public function setOrderreference($val)
	{
		$this->options["orderreference"] = $val;
		return $this;
	}
	

	public function setParenttransactionreference($val)
	{
		$this->options["parenttransactionreference"] = $val;
		return $this;
	}

	public function setRequesttypedescription($val)
	{
		$this->options["requesttypedescription"] = $val;
		return $this;
	}
	

	public function setTransactionreference($val)
	{
		$this->options["transactionreference"] = $val;
		return $this;
	}
	
}