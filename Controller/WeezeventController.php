<?php
namespace SSN\TherapassBundle\Controller;

use Doctrine\ORM\AbstractQuery;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\User\User;

use Oxygen\FrameworkBundle\Controller\OxygenController;

/**
 * Controller pour l'admin de Weezevent
 * 
 * @author lolozere
 *
 */
class WeezeventController extends OxygenController {
	/**
	 * Display the list of tickets created in weezevent
	 * 
	 */
	public function indexAction() {
		
		//Get list of weezevent used in event
		/*$tickets = $this->entitiesServer->getManager('oxygen_passbook.event_ticket')->getRepository()->createQueryBuilder('event_ticket')
			->distinct()->select('event_ticket.weezeventTicketId as ticketId')->innerJoin('event_ticket.event', 'event')
			->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);
		
		$tickets_id = array();
		foreach($tickets as $ticket) {
			$tickets_id[] = $ticket['ticketId'];
		}*/
		
		$tickets = $this->get('oxygen_weezevent.api')->getAllTickets();
		//echo nl2br(print_r($tickets, true)); exit();
		return $this->render('SSNTherapassBundle:Admin:weezevent.html.twig', array('tickets' => $tickets));
		
	}
	/**
	 * Display list of participants and precise if they have booked
	 * 
	 * @param string $weezeventTicketId
	 */	
	public function participantsAction($ticketId) {
		// Get participants on Weezevent
		$participants = $this->get('oxygen_weezevent.api')->getParticipants($ticketId);
		$eventName = null;
		if (!is_null($participants) && count($participants) > 0) {
			$tickets = $this->get('oxygen_weezevent.api')->getAllTickets();
			foreach($tickets as $ticket) {
				if ($ticket['id'] == $participants[0]['id_ticket']) {
					$eventName = $ticket['event']['name'] . ' - ' . $ticket['name'];
				}
			}
		} else {
			$participants = array();
		}
		// Get bookings
		$bookingTicketsNumber = $this->getEntitiesServer()->getManager('oxygen_passbook.booking_slot')->getRepository()
			->createQueryBuilder('booking_slot')
		 	->select('booking_slot.weezeventTicketNumber as weezeventTicketNumber')
		 	->innerJoin('booking_slot.eventTicket', 'event_ticket')
		 	->where('event_ticket.weezeventTicketId=:ticketId')->setParameter('ticketId', $ticketId)
			->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);
		$ticketsNumber = array();
		foreach($bookingTicketsNumber as $ticketNumber) {
			if (empty($ticketsNumber[$ticketNumber['weezeventTicketNumber']])) {
				$ticketsNumber[$ticketNumber['weezeventTicketNumber']] = 1;
			} else {
				$ticketsNumber[$ticketNumber['weezeventTicketNumber']] = $ticketsNumber[$ticketNumber['weezeventTicketNumber']] + 1;
			}
		}
		
		foreach($participants as $key => $participant) {
			if (empty($ticketsNumber[$participant['id_weez_ticket']])) {
				$participants[$key]['bookings'] = 0;
			} else {
				$participants[$key]['bookings'] = $ticketsNumber[$participant['id_weez_ticket']];
			}
		}
		return $this->render('SSNTherapassBundle:Admin:weezevent-participants.html.twig', array('participants' => $participants, 'eventName' => $eventName));
	}
	
}