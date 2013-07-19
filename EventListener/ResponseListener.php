<?php
namespace SSN\TherapassBundle\EventListener;

use Symfony\Component\HttpKernel\Event\FilterResponseEvent;

/**
 * Ecoute des erreurs survenant pendant l'exception
 * 
 * @author lolozere
 *
 */
class ResponseListener {
	
	/**
	 * @var Container
	 */
	protected $container;
	
	public function __construct($container) {
		$this->container = $container;
	}
	
	public function onResponse(FilterResponseEvent $event)	{
	    $event->getResponse()->headers->addCacheControlDirective('must-revalidate', true);
	    $event->getResponse()->setMaxAge(0);
	    $event->getResponse()->setClientTtl(0);
	    $event->getResponse()->setExpires(new \DateTime());
	    $event->getResponse()->headers->set('Pragma', 'no-cache');
	    $event->getResponse()->headers->set('Cache-Control', 'no-store');
	}
	
}