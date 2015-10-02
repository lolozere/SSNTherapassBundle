<?php
namespace SSN\TherapassBundle\Form\Type;

use Oxygen\FrameworkBundle\Form\Type\EntityEmbeddedFormType;

use Symfony\Component\OptionsResolver\OptionsResolverInterface;

use Symfony\Component\Form\FormBuilderInterface;

/**
 * Formulaire d'édition d'une personne réservant
 * 
 * @author lolozere
 *
 */
class BookingPersonFormType extends \Oxygen\PassbookBundle\Form\Type\BookingPersonFormType {
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);

		$builder->remove("name");
		
		$builder->add('firstname', 'text', array('required' => true, 'translation_domain' => 'oxygen_passbook_form'));
		$builder->add('lastname', 'text', array('required' => true, 'translation_domain' => 'oxygen_passbook_form'));

	}

}