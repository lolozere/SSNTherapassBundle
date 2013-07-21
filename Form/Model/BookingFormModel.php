<?php
namespace SSN\TherapassBundle\Form\Model;

use Oxygen\PassbookBundle\Form\Model\BookingFormModel as Base;

/**
 * Extend model booking for weezevent integration
 * 
 * @author lolozere
 *
 */
class BookingFormModel extends Base {
	
	protected $barcode;
	protected $useBarcode;
	
	/**
	* @param string $barcode
	* @return BookingFormModel
	*/
	public function setBarcode($barcode)
	{
	    $this->barcode = $barcode;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getBarcode()
	{
	    return $this->barcode;
	}
	
	/**
	* @param bool $useBarcode
	* @return BookingFormModel
	*/
	public function setUseBarcode($useBarcode)
	{
	    $this->useBarcode = $useBarcode;
	    return $this;
	}
	 
	/**
	* @return bool
	*/
	public function getUseBarcode()
	{
	    return $this->useBarcode;
	}
	
}