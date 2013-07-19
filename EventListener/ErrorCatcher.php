<?php
namespace SSN\TherapassBundle\EventListener;

use Symfony\Component\Debug\Exception\FlattenException;

use Symfony\Component\Finder\Exception\AccessDeniedException;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;

use Symfony\Component\DependencyInjection\Container;

/**
 * Ecoute des erreurs survenant pendant l'exception
 * 
 * @author lolozere
 *
 */
class ErrorCatcher {
	
	/**
	 * @var Container
	 */
	protected $container;
	
	public function __construct($container) {
		$this->container = $container;
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)	{
	    $exception = $event->getException();
	    if ($exception instanceof NotFoundHttpException || $exception instanceof AccessDeniedException) {
	    	//Rien à faire
	    } elseif ($this->container->getParameter('error_notification')) {
	    	//Nous envoyons un email
	    	$this->container->get('ssn_therapass.error.notifier')->sendMailError(
	    			FlattenException::create($exception, 500)
	    		);
	    }
	}
	
}