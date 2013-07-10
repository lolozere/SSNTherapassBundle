<?php
namespace SSN\TherapassBundle\Model;

/**
 * Interface implemented to represent event access pass 
 * 
 * @author lolozere
 *
 */
interface PassInterface {
	/**
	 * @return integer
	 */
	public function getId();
	/**
	 * Name of the pass. Example : Pass Bien-être 2013
	 * 
	 * @return string
	 */
	public function getName();
	/**
	 * 
	 * @param string $name
	 */
	public function setName($name);
	/**
	 * Date start of the event access
	 * 
	 * @return \DateTime
	 */
	public function getDateStart();
	/**
	 * 
	 * @param \DateTime $dateStart
	 */
	public function setDateStart(\DateTime $dateStart);
	/**
	 * Date end of the event access
	 *
	 * @return \DateTime
	 */
	public function getDateEnd();
	/**
	 * 
	 * @param \DateTime $dateEnd
	 */
	public function setDateEnd(\DateTime $dateEnd);
	
	
}