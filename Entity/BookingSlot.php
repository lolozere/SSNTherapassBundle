<?php
namespace SSN\TherapassBundle\Entity;

use Oxygen\PassbookBundle\Entity\BookingSlot as OxygenBookingSlot;

class BookingSlot extends OxygenBookingSlot {
	
	protected $weezeventTicketNumber;
	
	/**
	* @param string $weezeventTicketNumber
	* @return BookingSlot
	*/
	public function setWeezeventTicketNumber($weezeventTicketNumber)
	{
	    $this->weezeventTicketNumber = $weezeventTicketNumber;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getWeezeventTicketNumber()
	{
	    return $this->weezeventTicketNumber;
	}
	
}