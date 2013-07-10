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
		return $this->render('SSNTherapassBundle:Default:home.html.twig');
	}
	
}