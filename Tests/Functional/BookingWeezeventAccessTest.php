<?php
namespace SSN\TherapassBundle\Tests\Functional;

use SSN\TherapassBundle\Test\WebTestCase;

/**
 * Test suite for booking features with weezevent access
 *
 * @package SSN\TherapassBundle\Tests
 */
class BookingWeezeventAccessTest extends WebTestCase {

    public function setUp()
    {
        parent::setUp();
        $this->createDatabase();
    }

    /**
     * Test the authentification with a ticket
     *
     */
    public function testAuthentification()
    {
        /*$this->loadYamlFixtures(array(

        ));*/
        return;
    }

}