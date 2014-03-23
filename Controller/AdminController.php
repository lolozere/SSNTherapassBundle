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
	
	/**
	 * Configuration form of the application
	 *
	 * @return Response
	 */
	public function configAction() {
		
		$datasConfig = array();
		$datasConfig = array('commandLink' => $this->get('ssn_therapass.config')->getValueOf('commandLink'), 'alertBooking' => $this->get('ssn_therapass.config')->getValueOf('alertBooking'));
		
		$formBuilder = $this->createFormBuilder($datasConfig);

		$formBuilder->add('commandLink', 'url', array('required' => false, 'label' => $this->translate('app_config.form.commandLink.label', array(), 'ssn_therapass')));
		$formBuilder->add('alertBooking', 'textarea', array('required' => false, 'label' => $this->translate('app_config.form.alertBooking.label', array(), 'ssn_therapass')));
		$formBuilder->add('save', 'submit', array('label' => $this->translate('app_config.form.submit', array(), 'ssn_therapass')));
		$form = $formBuilder->getForm();
		
		$request = $this->getRequest();
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$data = $form->getData();
			foreach(array_keys($datasConfig) as $fieldName) {
				$this->get('ssn_therapass.config')->setConfig($fieldName, $data[$fieldName]);
			}
			$this->getDoctrine()->getEntityManager()->flush();
			$this->get('oxygen_framework.templating.messages')->addSuccess($this->translate('app_config.form.success', array(), 'ssn_therapass'));
		}
		
		return $this->render('SSNTherapassBundle:Admin:config.html.twig', array('form' => $form->createView()));
	}
}