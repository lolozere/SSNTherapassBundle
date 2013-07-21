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
		
		$form = null;
		if (count($events) > 0) {
			// get email
			$email = $this->get('oxygen_weezevent.api')->getEmail(
					$this->get('session')->get('weezeventTicketNumber'),
					$this->get('session')->get('weezeventTicketId')
				);
			
			$bookingPerson = null;
			$bookingPersonId = null;
			// Search by email
			$bookingPerson = $this->get('oxygen_framework.entities')->getManager('oxygen_passbook.booking_person')->getRepository()->findOneByEmail(
					$email
				);
			if (!is_null($bookingPerson)) {
				$bookingPersonId = $bookingPerson->getId();
			}
			
			// create form
			$form = $this->get('oxygen_framework.form')->getForm('oxygen_passbook_booking_form', array(
					'eventId' => $events[0]->getId(), 'bookingPersonId' => $bookingPersonId, 'email' => $email,
					'weezeventTicketNumber' => $this->get('session')->get('weezeventTicketNumber')
			));
			if ($form->isSubmitted()) {
				if ($form->process()) {
					// ok
				}
			}
		}
		
		return $this->render('SSNTherapassBundle:Booking:index.html.twig', array('events' => $events, 'email' => $email, 'form' => $form->createView()));
	}
	
}