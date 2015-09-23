<?php
namespace SSN\TherapassBundle\Fixtures;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use SSN\TherapassBundle\Entity\EventType;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoadEventTypeData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        // Get list of types usually in the database
        $event_types = $manager->getRepository("SSNTherapassBundle:EventType")->findAll();

        if (count($event_types) > 0) {
            echo __CLASS__  . " > event types already exist\n";
            return;
        }

@       $types_configuration = $this->container->getParameter('oxygen_passbook.event_types');

        foreach($types_configuration as $type) {
            $eventType = new EventType();
            $eventType->setName($this->container->get('translator')->trans('event_type.'.$type.".Name"));
            $manager->persist($eventType);
            $manager->flush();
        }
    }
}
