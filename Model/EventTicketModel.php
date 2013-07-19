<?php
namespace SSN\TherapassBundle\Model;

use Oxygen\PassbookBundle\Model\EventTicketModel as OxygenEventTicketModel;

/**
 * Ticket require to access event
 *
 * @author lolozere
 *
 */
abstract class EventTicketModel extends OxygenEventTicketModel {

	protected $weezeventTicketId;
	
	/**
	* @param string $weezeventTicketId
	* @return EventTicketModel
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