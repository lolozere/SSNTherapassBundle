<?php
namespace SSN\TherapassBundle\Form\Model;

use Oxygen\FrameworkBundle\Form\Model\EntityEmbeddedInterface;

use SSN\TherapassBundle\Model\EventProductModel;

class EventProductFormModel extends EventProductModel implements EntityEmbeddedInterface
{

	/**
	 * @var EventProductModel
	 */
	protected $entity;
	
	/**
	* @param EventProduct $eventTicket
	* @return EventProductFormModel
	*/
	public function setEntity($entity)
	{
	    $this->entity = $entity;
	    return $this;
	}
	 
	/**
	* @return EventProduct
	*/
	public function getEntity()
	{
	    return $this->entity;
	}

	public function getId()
	{
		return $this->entity->getId();
	}

}
