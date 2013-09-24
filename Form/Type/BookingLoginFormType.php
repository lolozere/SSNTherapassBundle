<?php
namespace SSN\TherapassBundle\Form\Type;

use Doctrine\ORM\AbstractQuery;

use Oxygen\FrameworkBundle\Model\EntitiesServer;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\AbstractType;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formulaire de connexion pour réserver avec son ticket Weezevent
 * 
 * @author lolozere
 *
 */
class BookingLoginFormType extends AbstractType {
	
	/**
	 * @var EntitiesServer
	 */
	protected $entitiesServer;
	
	public function __construct($entitiesServer, $weezeventApi) {
		$this->entitiesServer = $entitiesServer;
		$this->weezeventApi = $weezeventApi;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		// Get tickets used by events
		$tickets = $this->entitiesServer->getManager('oxygen_passbook.event_ticket')->getRepository()->createQueryBuilder('event_ticket')
			->distinct()->select('event_ticket.weezeventTicketId as ticketId')->innerJoin('event_ticket.event', 'event')
			->where('event.opened=:opened')->setParameter('opened', true)->getQuery()->getResult(AbstractQuery::HYDRATE_SCALAR);
		
		$tickets_allowed = array();
		foreach($tickets as $ticket) {
			$tickets_allowed[] = $ticket['ticketId'];
		}
		
		$tickets = $this->weezeventApi->getAllTickets();
		$choices = array();
		foreach($tickets as $ticket) {
			if (in_array($ticket['id'], $tickets_allowed)) {
				$choices[$ticket['id']] = $ticket['event']['name'] . ' > ' . $ticket['name'];
			}
		}
		
		$builder->add('weezeventTicketId', 'oxygen_weezevent_tickets_type', array(
				'multiple' => false, 'expanded' => false, 'empty_value' => 'ticket_empty_value', 
				'translation_domain' => 'ssn_therapass_form', 'choices' => $choices,
			));
		$builder->add('barcode', 'text', array('required' => true, 'translation_domain' => 'ssn_therapass_form'));
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver) {
		
	}
	
	public function getName()
	{
		return 'ssn_therapass_login_type';
	}
	
}