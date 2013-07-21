<?php
namespace SSN\TherapassBundle\Form\Handler;

use Symfony\Component\Form\FormError;

use Oxygen\PassbookBundle\Form\Handler\BookingForm as Base;

/**
 * Extend BookingForm to manage weezevent ticket
 * 
 * @author lolozere
 *
 */
class BookingForm extends Base {
	
	public function onLoad(array $params) {
		parent::onLoad($params);
		
		// Get if barcode
		foreach($this->orignalSlots as $bookingSlot) {
			if (!is_null($bookingSlot->getWeezeventTicketNumber())) {
				$this->getData()->setUseBarcode(true);
				$this->getData()->setBarcode($bookingSlot->getWeezeventTicketNumber());
			}
		}
		
		return $this;
	}
	
	public function onSubmit() {
		$successed = parent::onSubmit();
		if ($successed) {
			if ($this->getData()->getUseBarcode()) {
				if (is_null($this->getData()->getBarcode())) {
					$this->form->addError(new FormError('booking.barcode_required'));
					return false;
				}
			}
			$weezTicketId = null;
			if ($this->getData()->getUseBarcode()) {
				$ticketValid = $this->container->get('oxygen_weezevent.api')->isIdWeezTicketValid(
						$this->getData()->getBarcode(), $this->getData()->getEventTicket()->getWeezeventTicketId()
					);
				if (!$ticketValid) {
					$this->form->get('barcode')->addError(new FormError('booking.barcode_bad'));
					return false;
				}
				$weezTicketId = $this->getData()->getBarcode();
			}
			foreach($this->getData()->getPerson()->getBookingSlots() as $bookingSlot) {
				if ($bookingSlot->getEventProductSlot()->getEventProduct()->getEvent()->getId() == $this->event->getId()) {
					$bookingSlot->setWeezeventTicketNumber($weezTicketId);
				}
			}
			
			/*
			 * Check if ticket number (accros multiple multiple person) doesn't atteindre the max allowed
			 * @todo
			 */
			
			return true;
		} else {
			return false;
		}
	}
	
}