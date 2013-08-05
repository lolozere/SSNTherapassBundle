<?php
namespace SSN\TherapassBundle\Model;

use Oxygen\PassbookBundle\Model\EventProductModel as OxygenEventProductModel;

/**
 * Animation extended to add location
 *
 * @author lolozere
 *
 */
abstract class EventProductModel extends OxygenEventProductModel {
	
	protected $location;
	
	/**
	 * @param string $location
	 * @return EventProduct
	 */
	public function setLocation($location)
	{
		$this->location = $location;
		return $this;
	}
	
	/**
	 * @return string
	 */
	public function getLocation()
	{
		return $this->location;
	}
}