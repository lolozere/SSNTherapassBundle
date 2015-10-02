<?php
namespace SSN\TherapassBundle\Tests\Unit\Model;

use SSN\TherapassBundle\Entity\BookingPerson;

class BookingPersonUnitTest extends \PHPUnit_Framework_TestCase
{

    public function testBookingPersonName()
    {
        $person = new BookingPerson();
        $person->setFirstname("Pierre");
        $this->assertEquals("Pierre", $person->getName());

        $person->setLastname("Dupont");
        $this->assertEquals("DUPONT Pierre", $person->getName());
    }

}