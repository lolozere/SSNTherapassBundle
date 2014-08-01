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
class SecurityController extends OxygenController
{

	public function loginAction()
	{
		$request = $this->getRequest();
		$session = $request->getSession();
		
		// get the login error if there is one
		if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
			$error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
		} else {
			$error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
			$session->remove(SecurityContext::AUTHENTICATION_ERROR);
		}
		
		return $this->render('SSNTherapassBundle:Security:login.html.twig',
				array(
					// last username entered by the user
					'last_username' => $session->get(SecurityContext::LAST_USERNAME), 'error' => $error));
	}

	/**
	 * Action pour se connecter aux réservations
	 */
	public function loginBookingAction()
	{
		$form = $this->get('oxygen_framework.form')->getForm('ssn_therapass_login_form');
		if ($form->isSubmitted()) {
			if ($form->process()) {
				return $this->redirect($this->generateUrl('ssn_therapass_booking_index'));
			}
		}
		
		$alertBooking = $this->get('ssn_therapass.config')->getValueOf('alertBooking');
		$closeBooking = $this->get('ssn_therapass.config')->getValueOf('closeBooking');
		
		return $this->render('SSNTherapassBundle:Security:login-ticket.html.twig',
				array('form' => $form->createView(), 'alertBooking' => $alertBooking, 'closeBooking' => $closeBooking));
	}
}