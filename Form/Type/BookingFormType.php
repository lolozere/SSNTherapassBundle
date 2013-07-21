<?php
namespace SSN\TherapassBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

use Oxygen\PassbookBundle\Form\Type\BookingFormType as Base;

/**
 * Extend form type booking for weezevent integration
 *  
 * @author lolozere
 *
 */
class BookingFormType extends Base {
	
	public function buildForm(FormBuilderInterface $builder, array $options) {
		parent::buildForm($builder, $options);
		
		$builder->add('barcode', 'text', array('required' => false, 'translation_domain' => 'oxygen_passbook_form'));
		$builder->add('useBarcode', 'checkbox', array(
				'required' => false, 'translation_domain' => 'oxygen_passbook_form'
			));
	}
	
}