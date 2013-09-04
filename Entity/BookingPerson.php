<?php
namespace SSN\TherapassBundle\Entity;

use Oxygen\PassbookBundle\Entity\BookingPerson as OxygenBookingPerson;

class BookingPerson extends OxygenBookingPerson {
	
	protected $reference;
	
	/**
	* @param string $reference
	* @return BookingPerson
	*/
	public function setReference($reference)
	{
	    $this->reference = $reference;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getReference()
	{
	    return $this->reference;
	}
	
}