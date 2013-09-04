<?php
namespace SSN\TherapassBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\SecurityContext;

use Oxygen\FrameworkBundle\Controller\OxygenController;

/**
 * Controller pour les accès sécurisés
 * 
 * @author lolozere
 *
 */
class BookingController extends OxygenController {
	
	public function indexAction()
	{
		// Check if event pass
		$events = $this->get('oxygen_framework.entities')->getManager('oxygen_passbook.event')->getRepository()->findByWeezeventTicketId(
				$this->get('session')->get('weezeventTicketId')
			);
		
		// get email
		$email = $this->get('oxygen_weezevent.api')->getEmail(
				$this->get('session')->get('weezeventTicketNumber'),
				$this->get('session')->get('weezeventTicketId')
		);
		
		// Get if bookings for events
		$bookPersons = $this->get('oxygen_framework.entities')->getManager('oxygen_passbook.booking_person')->getRepository()->findByWeezeventTicketNumber(
				$this->get('session')->get('weezeventTicketNumber')
			);
		$bookings = array();
		foreach($bookPersons as $bookPerson) {
			foreach($bookPerson->getBookingSlots() as $bookingSlot) {
				if ($bookingSlot->getWeezeventTicketNumber() == $this->get('session')->get('weezeventTicketNumber')) {
					if (empty($bookings[$bookingSlot->getEventProductSlot()->getEventProduct()->getEvent()->getId()])) {
						$bookings[$bookingSlot->getEventProductSlot()->getEventProduct()->getEvent()->getId()] = array();
					}
					$bookings[$bookingSlot->getEventProductSlot()->getEventProduct()->getEvent()->getId()][] = $bookingSlot;
				}
			}
		}
		
		return $this->render('SSNTherapassBundle:Booking:index.html.twig', array('events' => $events, 'email' => $email, 'bookings' => $bookings));
	}
	
	public function bookingAction($eventId) {
		
		$event = $this->get('oxygen_framework.entities')->getManager('oxygen_passbook.event')->getRepository()->find(
				$eventId
			);
		
		if (is_null($event)) {
			throw $this->createNotFoundException($this->translate('booking.book.notfound_event', array('id' => $eventId), 'ssn_therapass'));
		}
		
		// get email
		$email = $this->get('oxygen_weezevent.api')->getEmail(
				$this->get('session')->get('weezeventTicketNumber'),
				$this->get('session')->get('weezeventTicketId')
		);
		
		$bookingPerson = null;
		$bookingPersonId = null;
		// Search by email
		$bookingPerson = $this->get('oxygen_framework.entities')->getManager('oxygen_passbook.booking_person')->getRepository()->findOneBy(
				array('email' =>$email, 'reference' => $this->get('session')->get('weezeventTicketNumber'))
			);
		if (!is_null($bookingPerson) > 0) {
			$bookingPersonId = $bookingPerson->getId();
		}
			
		// get default ticket
		$eventTicket = $this->get('oxygen_framework.entities')->getManager('oxygen_passbook.event_ticket')->getRepository()->findOneBy(array(
				'weezeventTicketId' => $this->get('session')->get('weezeventTicketId'),
				'event' => $eventId
			));
		if (is_null($eventTicket)) {
			throw $this->createNotFoundException($this->translate('booking.book.notfound_event_ticket', array('name' => $event->getName(), 'weezeventTicketId' => $this->get('session')->get('weezeventTicketId')), 'ssn_therapass'));
		}
			
		// create form
		$form = $this->get('oxygen_framework.form')->getForm('oxygen_passbook_booking_form', array(
				'eventId' => $event->getId(), 'bookingPersonId' => $bookingPersonId, 'email' => $email,
				'weezeventTicketNumber' => $this->get('session')->get('weezeventTicketNumber'),
				'eventTicket' => $eventTicket
		));
		if ($form->isSubmitted()) {
			if ($form->process()) {
				return $this->redirect($this->generateUrl('ssn_therapass_booking_index'));
			}
		}
		
		return $this->render('SSNTherapassBundle:Booking:book.html.twig', array('event' => $event, 'email' => $email, 'form' => $form->createView()));
		
	}
	
}