php-stpp
--------

This masterpiece is currently in development, however, it's doing things
properly as it should - including 3D Secure support, however awkward that
may be.

I've tried to make this as versatile as possible, matching various different
coding patterns, as well, as a developer I know annoying it can be to adjust to
someone else's patterns when you think your own is superior.

Note: Amounts do not match the scheme applied to Secure Trading's API, as I
think placing orders in pence for things that are usually in pounds is somewhat
counter intuitive. (GBP; stirling pounds as opposed to pence) 

Note: There's no validation as of yet. I intend to add validation in whenever
I get the chance.

Example
-------

	<?php
	
	$stapi = new STAPI("127.0.0.1", 5000);
	
	$stapi->setAlias("test_typefish0000");
	
	$stapi->setOperationSiteReference("test_typefish0000")
	      ->setOperationAccountTypeDescription("ECOM");
	
	$billing = $this->stapi->getBilling();
	
	$billing->setAmount(12.50)
	        ->setCurrency("GBP")
	        ->setPaymentType("VISA")
	        ->setPaymentCardNumber("4111111111111111")
	        ->setPaymentExpiryDate([ 10, 2016 ])
	        ->setPaymentSecurityCode(123);
	
	# set billing information
	$billing->setNamePrefix("Mr")
	        ->setFirstName("David")
	        ->setLastName("Weston")
	        ->setPremise("No 789")
	        ->setStreet("Test Street")
	        ->setTown("Corby")
	        ->setCounty("Northamptonshire")
	        ->setPostcode("TE45 6ST")
	        ->setCountry("GB");
	
	$response = $stapi->call("auth");
	
	if($response->isSuccessful())
	    echo "Success!";
	else
		echo "Failure - ".$response->getErrorMessage();
