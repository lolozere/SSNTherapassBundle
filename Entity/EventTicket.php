<?php
namespace SSN\TherapassBundle\Entity;

use Oxygen\PassbookBundle\Entity\EventTicket as OxygenEventTicket;

/**
 * Ticket require to access event
 * 
 * @author lolozere
 *
 */
class EventTicket extends OxygenEventTicket
{

	protected $weezeventTicketId;
	
	/**
	* @param string $weezeventTicketId
	* @return EventTicket
	*/
	public function setWeezeventTicketId($weezeventTicketId)
	{
	    $this->weezeventTicketId = $weezeventTicketId;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getWeezeventTicketId()
	{
	    return $this->weezeventTicketId;
	}
	
}
