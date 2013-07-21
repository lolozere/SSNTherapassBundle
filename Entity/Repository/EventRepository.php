<?php
namespace SSN\TherapassBundle\Entity\Repository;

use Oxygen\PassbookBundle\Entity\Repository\EventRepository as Base;

class EventRepository extends Base {
	
	public function findByWeezeventTicketId($weezeventTicketId) {
		return $this->createQueryBuilder('event')->innerJoin('event.tickets', 'event_ticket')
			->where('event_ticket.weezeventTicketId=:id')->setParameter('id', $weezeventTicketId)
			->andWhere('event.dateEnd>:now')->setParameter('now', new \DateTime())
			->groupBy('event.id')->getQuery()->getResult();
	}
	
}