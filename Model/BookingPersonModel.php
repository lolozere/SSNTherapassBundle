<?php
namespace SSN\TherapassBundle\Model;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Person booking slot
 * 
 * @author lolozere
 *
 */
abstract class BookingPersonModel extends \Oxygen\PassbookBundle\Model\BookingPersonModel {

	protected $reference;

	private $firstname;
	private $lastname;

	/**
	 * @param string $reference
	 * @return BookingPerson
	 */
	public function setReference($reference)
	{
		$this->reference = $reference;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getReference()
	{
		return $this->reference;
	}

	protected function refreshName()
	{
		if (!empty($this->lastname) && !empty($this->firstname))
			$this->name = join(' ', array(strtoupper($this->lastname), $this->firstname));
		else
			$this->name = join('', array(strtoupper($this->lastname), $this->firstname));
	}

	/**
	 * @return mixed
	 */
	public function getFirstname()
	{
		return $this->firstname;
	}

	/**
	 * @param mixed $firstname
	 */
	public function setFirstname($firstname)
	{
		$this->firstname = $firstname;
		$this->refreshName();
	}

	/**
	 * @return mixed
	 */
	public function getLastname()
	{
		return $this->lastname;
	}

	/**
	 * @param mixed $lastname
	 */
	public function setLastname($lastname)
	{
		$this->lastname = $lastname;
		$this->refreshName();
	}
	
}