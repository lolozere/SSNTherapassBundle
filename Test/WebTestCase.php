<?php
namespace SSN\TherapassBundle\Test;

use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Client;

/**
 * WebTestCase features for functionals tests of SSN
 *
 * @package SSN\TherapassBundle\Test
 */
class WebTestCase extends \Symfony\Bundle\FrameworkBundle\Test\WebTestCase {

    /**
     * @var Client
     */
    protected $client;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * @return \Symfony\Component\DependencyInjection\ContainerInterface
     */
    protected function getContainer()
    {
        return $this->client->getContainer();
    }

    protected function createDatabase()
    {
        if (is_null($this->client))
            throw new \RuntimeException("TestCase must be set up before create database");

        $em = $this->getContainer()->get('doctrine')->getManager();
        $metadatas = $em->getMetadataFactory()->getAllMetadata();
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        $schemaTool->createSchema($metadatas);
    }

}