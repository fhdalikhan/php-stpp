<?php
/**
 *	PHP based wrapper for SecureTrading's new STPP protocol.
 *	
 *	This is the object that represents updated settlement details.
 *	
 *	@version: 1.0
 *	@author: David Weston <stpp@typefish.co.uk>
 */


class STPPSettlement extends STPPObject
{
	/**
	 *	Set the settlement due date. Please note that you cannot
	 *	settle further than seven days after the transaction has
	 *	been authorised.
	 */
	public function setDate($settledate)
	{
		if(is_array($settledate))
		{
			$day = null;
			$month = null;
			$year = null;
			
			if(isset($settledate["day"]))
			{
				$day = $settledate["day"];
				$month = $settledate["month"];
				$year = $settledate["year"];
			}
			else
			{
				$day = $settledate[0];
				$month = $settledate[1];
				$year = $settledate[2];
			}
			
			$day = str_pad($day, 2, "0", STR_PAD_LEFT);
			$month = str_pad($month, 2, "0", STR_PAD_LEFT);
			
			if($year < 100)
				$year = 2000 + $year;
			
			$settledate = $month."/".$year;
		}
		elseif(is_numeric($settledate) || is_int($settledate))
		{
			$settledate = date("d/m/Y", $settledate);
		}
		
		$this->options["payment"]["settleduedate"] = (string) $settledate;
		
		return $this;
	}
	
	
	/**
	 *	Set the status of the settlement. You can find more information
	 *	on this from the STPP documentation.
	 */
	public function setStatus($status)
	{
		$this->options["payment"]["settlestatus"] = (string) $settledate;
		
		return $this;
	}
}