<?php
namespace common\components;

use common\models\Activity;


class PriceManager{

	/**
	 * Compute the total price for a list of activities
	 * @param  array $activities list of activities
	 * @return float
	 */
	public static function computeTotalPrice($activities){
		$total_price = 0;
		foreach ($activities as $activity) {
			$total_price+= $activity->price;
		}
		return $total_price;
	}

}