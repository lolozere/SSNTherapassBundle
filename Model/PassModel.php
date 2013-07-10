<?php
namespace SSN\TherapassBundle\Model;

/**
 * Model representing event access pass 
 * 
 * @author lolozere
 *
 */
class PassModel implements PassInterface {
	
	protected $id;
	protected $name;
	protected $dateStart;
	protected $dateEnd;
	
	/**
	 * @return integer
	 */
	public function getId() { return $this->id; }
	
	/**
	* @param string $name
	* @return PassModel
	*/
	public function setName($name)
	{
	    $this->name = $name;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getName()
	{
	    return $this->name;
	}
	
	/**
	* @param \DateTime $dateStart
	* @return PassModel
	*/
	public function setDateStart($dateStart)
	{
	    $this->dateStart = $dateStart;
	    return $this;
	}
	 
	/**
	* @return \DateTime
	*/
	public function getDateStart()
	{
	    return $this->dateStart;
	}
	
	/**
	* @param \DateTime $dateEnd
	* @return PassModel
	*/
	public function setDateEnd($dateEnd)
	{
	    $this->dateEnd = $dateEnd;
	    return $this;
	}
	 
	/**
	* @return \DateTime
	*/
	public function getDateEnd()
	{
	    return $this->dateEnd;
	}
	
}