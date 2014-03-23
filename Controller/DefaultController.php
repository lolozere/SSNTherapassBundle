<?php
namespace SSN\TherapassBundle\Controller;

use Oxygen\FrameworkBundle\Controller\OxygenController;

/**
 * Controller pour les routes par défaut
 *
 * @author lolozere
 *
 */
class DefaultController extends OxygenController {
	
	/**
	 * Home page
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 *
	 */
	public function indexAction() {
		
		$urlCommand = $this->get('ssn_therapass.config')->getValueOf('commandLink');
		$alertBooking = $this->get('ssn_therapass.config')->getValueOf('alertBooking');
		
		return $this->render('SSNTherapassBundle:Default:home.html.twig', array('urlCommand' => $urlCommand, 'alertBooking' => $alertBooking));
	}
	
}