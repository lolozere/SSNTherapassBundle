<?php
namespace SSN\TherapassBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Oxygen\PassbookBundle\Form\Type\EventTicketFormType as OxygenEventTicketFormType;

/**
 * Formulaire d'édition d'un ticket d'accès à un évènement
 * 
 * @author lolozere
 *
 */
class EventTicketFormType extends OxygenEventTicketFormType {

	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);
		$builder->add('weezeventTicketId', 'oxygen_weezevent_tickets_type', array('multiple' => false, 'expanded' => false, 'empty_value' => 'form.choice.empty_value'));
	}
	
	
	
}