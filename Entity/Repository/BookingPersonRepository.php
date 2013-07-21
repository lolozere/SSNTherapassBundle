<?php
namespace SSN\TherapassBundle\Entity\Repository;

use Oxygen\PassbookBundle\Entity\Repository\BookingPersonRepository as Base;

/**
 * 
 * 
 * @author lolozere
 *
 */
class BookingPersonRepository extends Base
{
	/**
	 * Return persons using this number in a booking
	 * 
	 * @param string $weezeventTicketNumber
	 */
	public function findByWeezeventTicketNumber($weezeventTicketNumber) {
		return $this->createQueryBuilder('booking_person')
			->innerJoin('booking_person.bookingSlots', 'booking_slot')
			->where('booking_slot.weezeventTicketNumber=:number')->setParameter('number', $weezeventTicketNumber)
			->groupBy('booking_person.id')->getQuery()->getResult();
	}
	
}