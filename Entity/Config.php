<?php
namespace SSN\TherapassBundle\Entity;

/**
 * Config data of application
 *
 * @author lolozere
 *
 */
class Config {
	
	/**
	 *
	 * @var string
	 */
	protected $id;

	/**
	 *
	 * @var string
	 */
	protected $value;
	
	/**
	* @param string $id
	* @return Config
	*/
	public function setId($id)
	{
	    $this->id = $id;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getId()
	{
	    return $this->id;
	}
	
	
	/**
	* @param string $value
	* @return Config
	*/
	public function setValue($value)
	{
	    $this->value = $value;
	    return $this;
	}
	 
	/**
	* @return string
	*/
	public function getValue()
	{
	    return $this->value;
	}
	
}