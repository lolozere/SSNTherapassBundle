<?php
namespace SSN\TherapassBundle\Form\Handler;

use Doctrine\ORM\UnitOfWork;

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
		
		if (!empty($params['weezeventTicketNumber'])) {
			$this->getData()->setUseBarcode(true);
			$this->getData()->setBarcode($params['weezeventTicketNumber']);
		} else {
			// Get if barcode
			foreach($this->orignalSlots as $bookingSlot) {
				if (!is_null($bookingSlot->getWeezeventTicketNumber())) {
					$this->getData()->setUseBarcode(true);
					$this->getData()->setBarcode($bookingSlot->getWeezeventTicketNumber());
				}
			}
		}
		if (!empty($params['email'])) {
			$this->options['person_options']['email_disabled'] = true;
			$this->options['person_options']['label'] = 'form.booking.user_data';
			$this->getData()->getPerson()->setEmail($params['email']);
		}
		
		return $this;
	}
	
	/**
	 * Search unique person (or create) to registrer bookings
	 *
	 */
	protected function getBookingPerson() {
		//Get booking person by email
		$bookingPerson =  $this->container->get('oxygen_framework.entities')->getManager('oxygen_passbook.booking_person')->getRepository()->findOneBy(
				array('email' => $this->getData()->getPerson()->getEmail(), 'reference'  => $this->getData()->getBarcode())
		);
		if (!is_null($bookingPerson)) {
			foreach($bookingPerson->getBookingSlots() as $bookingSlot) {
				if ($bookingSlot->getEventTicket()->getId() == $this->getData()->getEventTicket()->getId()) {
					$this->form->addError(new FormError('booking.errors.booking_exist', null, array(
							'%mail%' => $this->getData()->getPerson()->getEmail(),
							'%name%' => $this->options['event']->getName(),
							'%barcode%' => $this->getData()->getBarcode(),
					)));
					break;
				}
			}
			// Transfer data to existing person
			$bookingPerson->setName($this->getData()->getPerson()->getName());
			$bookingPerson->setEmail($this->getData()->getPerson()->getEmail());
			$this->getData()->setPerson($bookingPerson);
		} else {
			$bookingPerson = $this->getData()->getPerson();
			$this->container->get('doctrine.orm.entity_manager')->persist($bookingPerson);
		}
		return $bookingPerson;
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
			
			// Set reference for person
			$this->getData()->getPerson()->setReference($weezTicketId);
			
			$totalBookings = 0;
			foreach($this->getData()->getPerson()->getBookingSlots() as $bookingSlot) {
				if ($bookingSlot->getEventTicket()->getId() == $this->getData()->getEventTicket()->getId()) {
					$bookingSlot->setWeezeventTicketNumber($weezTicketId);
					$totalBookings++;
				}
			}
			
			/*
			 * Check if ticket number (accros multiple multiple person) doesn't atteindre the max allowed
			 * @todo
			 */
			if ($this->getData()->getUseBarcode() && !is_null($this->getData()->getBarcode())) {
				$bookings = $this->container->get('oxygen_framework.entities')->getManager('oxygen_passbook.booking_slot')->getRepository()->findBy(array('weezeventTicketNumber' => $this->getData()->getBarcode()));
				$totalBookingsOther = 0;
				foreach($bookings as $booking) {
					if (is_null($this->getData()->getPerson()->getId()) || $booking->getBookingPerson()->getId() != $this->getData()->getPerson()->getId()) {
						$totalBookingsOther++;
					}
				}
				if ($totalBookingsOther>=$this->getData()->getEventTicket()->getLimitAnimations()) {
					$this->form->addError(new FormError('booking.limits_by_other_ending', null, array('%bookings%' => $totalBookingsOther)));
					return false;
				} elseif (($totalBookingsOther+$totalBookings)>$this->getData()->getEventTicket()->getLimitAnimations()) {
					$this->form->addError(new FormError('booking.limits_by_other', null, array('%bookings%' => $totalBookingsOther, '%max%' => ($this->getData()->getEventTicket()->getLimitAnimations()-$totalBookingsOther))));
					return false;
				}
			}
			
			return true;
		} else {
			return false;
		}
	}
	
	public function onSuccess() {
		/*
		 * Send email
		*/
		$swiftMessage = $this->container->get('ssn_therapass.mailer')->getMailMessage(
				'SSNTherapassBundle:Booking:mail-confirm.html.twig', array(
						'is_changing' => !is_null($this->getData()->getPerson()->getId()),
						'bookings' => $this->getData()->getPerson()->getBookingSlots(),
						'event' => $this->event,
			));
		$this->container->get('ssn_therapass.mailer')->send($swiftMessage, $this->getData()->getPerson()->getEmail());
		
		return parent::onSuccess();
	}
	
}