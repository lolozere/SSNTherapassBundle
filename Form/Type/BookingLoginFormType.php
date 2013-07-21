<?php
namespace SSN\TherapassBundle\Form\Type;

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
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('weezeventTicketId', 'oxygen_weezevent_tickets_type', array(
				'multiple' => false, 'expanded' => false, 'empty_value' => 'ticket_empty_value', 
				'translation_domain' => 'ssn_therapass_form'
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