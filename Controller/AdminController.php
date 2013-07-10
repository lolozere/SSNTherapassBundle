<?php
namespace SSN\TherapassBundle\Controller;

use Oxygen\FrameworkBundle\Controller\OxygenController;

/**
 * Controller pour les routes par défaut
 * 
 * @author lolozere
 *
 */
class AdminController extends OxygenController {
	
	/**
	 * Home page Administration
	 * 
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 * 
	 */
	public function indexAction() {
		return $this->render('SSNTherapassBundle:Admin:home.html.twig');
	}
	
	/**
	 *  Administration Menu
	 *
	 * @author Laurent Chedanne <laurent@chedanne.pro>
	 *
	 */
	public function menuAction() {
		return $this->render('SSNTherapassBundle:Admin:menu.html.twig');
	}
	
}