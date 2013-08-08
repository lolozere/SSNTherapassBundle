<?php
namespace SSN\TherapassBundle\Entity\Repository;

use Oxygen\PassbookBundle\Entity\Repository\EventProductRepository as Base;

/**
 * Permet de retrouver des
 * @author lolozere
 *
 */
class EventProductRepository extends Base
{
	
	public function findAllsForBooking($eventId) {
		$products = parent::findAllsForBooking($eventId);
		$productsToOrder = array();
		foreach($products as $product) {
			$matches = array();
			if (preg_match('/([0-9]+)/', $product->getLocation(), $matches)) {
				$order = substr_replace("0000", $matches[0], -strlen($matches[0]));
			} else {
				$order = '9999';
			}
			$productsToOrder[$order.'#'.$product->getName()] = $product;
		}
		ksort($productsToOrder);
		return array_values($productsToOrder);
	}
	
}