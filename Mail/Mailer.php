<?php
namespace SSN\TherapassBundle\Mail;

use Symfony\Component\DependencyInjection\Container;

use Symfony\Component\Security\Core\SecurityContext;

/**
 * Classe de gestion d'envoi d'email
 * 
 * @author lolozere
 *
 */

class Mailer {
	
	/**
	 * @var Container
	 */
	protected $container;
	/**
	 * 
	 * @var \Twig_Environment
	 */
	protected $twig;
	
	public function __construct($container, \Twig_Environment $twig) {
		$this->container = $container;
		$this->twig = $twig;
	}
	
	protected function getDefaultFrom() {
		return $this->container->get('translator')->trans('salon.name', array(), 'ssn_therapass') . ' <'. $this->container->getParameter('mail_contact') .'>';
	}
	
	/**
	 * Flush sent email
	 * 
	 * From digging some Symfony and SwiftMailer code, I can see that the memory spool is flushed on the kernel.terminate event 
	 * that occurs after a response was sent. Here you can force to flush spool
	 */
	public function flush() {
		$transport = $this->container->get('mailer')->getTransport();
		if (!$transport instanceof \Swift_Transport_SpoolTransport) {
		    return;
		}
		$spool = $transport->getSpool();
		if (!$spool instanceof \Swift_MemorySpool) {
		    return;
		}
		$spool->flushQueue($this->container->get('swiftmailer.transport.real'));
	}
	
	/**
	 * Envoi le message à une liste de personnes avec comme expéditeur la personne connectée
	 *
	 * @param \Swift_Message $message
	 * @param array|string $recipients Destinataires
	 * @param string $from Expéditeur au format : Laurent <laurent@mail.com>
	 */
	public function send(\Swift_Message $message, $recipients, $from = null) {
		// From
		if (is_null($from))
			$from = $this->getDefaultFrom();
		
		// Check recipients
		$matches = array();
		preg_match('/^(.*)\<(.+)\>$/', $from, $matches);
		if (count($matches) == 3) {
			$message->setFrom($matches[2], trim($matches[1]));
		} else {
			throw new \Exception(sprintf("From format invalid : %s. Must be like Laurent <laurent@mail.com>", $from));
		}
		$message->setTo($recipients);
		$this->container->get('mailer')->send($message);
	}
	
	/**
	 * Fonction permettant de générer un message mail à partir d'un template
	 * 
	 * @param string $template Template path
	 * @param array $params contient des paramètres à fournir aux templates
	 * @return \Swift_Message $message
	 */
	public function getMailMessage($template, $params) {
		
		$params = array_merge($this->twig->getGlobals(), $params);
		
		//Chargement du template
		$template = $this->twig->loadTemplate($template); //on récupére les blocks
		$header_right = $template->renderBlock('header_right', $params);
		if (empty($header_right))
			$header_right = null;
		
		$subject = $template->renderBlock('mail_subject', $params);
		$bodyHtml = $template->renderBlock('mail_html_content', $params);
		$bodyTxt = $template->renderBlock('mail_txt_content', $params);
		
		$message = \Swift_Message::newInstance();
		$message->setSubject($subject);
		$message->setBody($bodyHtml, 'text/html');
		$message->addPart($bodyTxt, 'text/plain', 'utf-8');
				
		return $message;
	}
}