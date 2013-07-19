<?php
namespace SSN\TherapassBundle\Form\Model;

use Oxygen\FrameworkBundle\Form\Model\EntityEmbeddedInterface;

use SSN\TherapassBundle\Model\EventTicketModel;

class EventTicketFormModel extends EventTicketModel implements EntityEmbeddedInterface
{

	/**
	 * @var EventTicketInterface
	 */
	protected $eventTicket;
	
	/**
	* @param EventTicketInterface $eventTicket
	* @return EventTicketFormModel
	*/
	public function setEntity($entity)
	{
	    $this->eventTicket = $entity;
	    return $this;
	}
	 
	/**
	* @return EventTicketInterface
	*/
	public function getEntity()
	{
	    return $this->eventTicket;
	}

	public function getId()
	{
		return $this->eventTicket->getId();
	}

}
