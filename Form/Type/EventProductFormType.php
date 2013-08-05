<?php
namespace SSN\TherapassBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Oxygen\PassbookBundle\Form\Type\EventProductFormType as Base;

/**
 * Extension du formulaire d'édition d'un produit dans évènement pour ajouter l'emplacement
 * 
 * @author lolozere
 *
 */
class EventProductFormType extends Base {
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildForm($builder, $options);
		$builder->add('location', 'text', array('required' => true, 'translation_domain' => 'oxygen_passbook_form'));
	}

}