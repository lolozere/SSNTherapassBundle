<?php
namespace SSN\TherapassBundle\Form\Handler;

use Symfony\Component\Security\Core\User\User;

use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

use Symfony\Component\Form\FormError;

use Oxygen\FrameworkBundle\Form\Form;

/**
 * Extend BookingForm to manage weezevent ticket
 * 
 * @author lolozere
 *
 */
class BookingLoginForm extends Form {
	
	protected $data;
	
	public function getData() {
		if (is_null($this->data)) {
			$dataClass = $this->getDataClass();
			$this->data = new $dataClass();
		}
		return $this->data;
	}
	
	public function onLoad(array $params) {
		return $this;
	}
	
	public function onSubmit() {
		// Search event with this ticket
		$eventTickets = $this->container->get('oxygen_framework.entities')->getManager('oxygen_passbook.event_ticket')
			->getRepository()->findBy(array('weezeventTicketId' => $this->getData()->weezeventTicketId));
		if (count($eventTickets) <= 0) {
			$this->form->addError(new FormError('form_login.errors.noevent'));
			return false;
		}
		
		// Check if good billet
		$ticketValid = $this->container->get('oxygen_weezevent.api')->isIdWeezTicketValid(
				$this->getData()->barcode, $this->getData()->weezeventTicketId
		);
		if (!$ticketValid) {
			$this->form->get('barcode')->addError(new FormError('booking.barcode_bad'));
			return false;
		}
		
		// Set user security
		$user = new User('booker', null);
		$token = new UsernamePasswordToken($user, null, 'booking_area', array('ROLE_BOOKER'));
		$this->container->get('security.context')->setToken($token);
		
		$this->container->get('session')->set('weezeventTicketNumber', $this->getData()->barcode);
		$this->container->get('session')->set('weezeventTicketId', $this->getData()->weezeventTicketId);
		
		return true;
	}
	
	public function onSuccess() {
		return true;
	}
	
}