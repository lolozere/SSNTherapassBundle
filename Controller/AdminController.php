<?php
namespace SSN\TherapassBundle\Controller;

use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Security\Core\User\User;

use Oxygen\FrameworkBundle\Controller\OxygenController;

/**
 * Controller pour les routes par défaut
 * 
 * @author lolozere
 *
 */
class AdminController extends OxygenController {
	
	public function encodePasswordAction($password) {
		$factory = $this->get('security.encoder_factory');
		$user = new User('admin', $password);
		
		$encoder = $factory->getEncoder($user);
		$password = $encoder->encodePassword($user->getPassword(), $user->getSalt());
		return Response::create($password);
	}
	
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