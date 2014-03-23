<?php
namespace SSN\TherapassBundle\Model;

use Doctrine\ORM\EntityManager;
use SSN\TherapassBundle\Entity\Config;

/**
 * Service to manager config application
 *
 * @author lolozere
 *
 */
class ApplicationConfig {

	/**
	 *
	 * @var EntityManager
	 */
	protected $em;
	
	/**
	 *
	 * @param EntityManager $em
	 */
	public function __construct($em) {
		$this->em = $em;
	}
	/**
	 * Set a value of a config id
	 *
	 * @param string $id
	 * @param string $value
	 */
	public function setConfig($id, $value) {
		$config = $this->em->getRepository('SSNTherapassBundle:Config')->find($id);
		if (is_null($config)) {
			$config = new Config();
		}
		$config->setValue($value);
		if (is_null($config->getId())) {
			$config->setId($id);
			$this->em->persist($config);
		}
	}
	/**
	 * Return value of a config
	 *
	 * @param string $id
	 * @return string
	 */
	public function getValueOf($id) {
		$config = $this->em->getRepository('SSNTherapassBundle:Config')->find($id);
		if (is_null($config)) {
			return null;
		}
		return $config->getValue();
	}
}