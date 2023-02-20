<?php
namespace common\components;

use common\models\Activity;
use common\models\ReductionRule;


class PriceManager{

	var $event;

	function __construct($event){
		$this->event = $event;
	}

	/**
	 * Get the valid reductions
	 * @param  array $participations The selected activities with the quantities
	 * @return boolean
	 */
	public function getValidReductions($model){
		$participations = $model->participations;
		$reductions = $this->event->reductions;
		$activitiesCounts = [];
		$activities = [];
		// Counting the activities per group to check if reductions are valid
		foreach($participations as $participation){
			$activity = $participation->activity;
			if(!isset($activitiesCounts[$activity->activityGroup->id]))
				$activitiesCounts[$activity->activityGroup->id] = 0;
			$activitiesCounts[$activity->activityGroup->id]++;
			$activities[$activity->id] = true;
		}
		// Building the array with the valid reductions
		$validReductions = [];
		foreach($reductions as $reduction){
			$isValid = true;
			$today = date('Y-m-d');
			if(($today > $reduction->validity_end && !empty($reduction->validity_end)) || ($today < $reduction->validity_start && !empty($reduction->validity_start))){
				$isValid = false;
				continue;
			}
			// Checking all rules for the reduction are verified
			foreach($reduction->rules as $rule){
				if(!isset($activitiesCounts[$rule->activityGroup->id])){
					$isValid = false;
					break;
				}
				if($activitiesCounts[$rule->activityGroup->id] < $rule->lower_limit){
					$isValid = false;
					break;
				}
				if($activitiesCounts[$rule->activityGroup->id] > $rule->higher_limit){
					$isValid = false;
					break;
				}
				if(isset($rule->activity_id) && !empty($rule->activity_id) && !isset($activities[$rule->activity_id])){
					$isValid = false;
					break;
				}
			}
			if(!empty($reduction->promocode) && $model->promocode != $reduction->promocode)
				$isValid = false;
			if($isValid)
				$validReductions[$reduction->id] = $reduction;
		}
		// Removing the reductions incompatible with other reductions
		foreach($validReductions as $reduction){
			if(sizeof($reduction->incompatibleReductions) > 0){
				foreach($reduction->incompatibleReductions as $incompatibleReduction){
					if(isset($validReductions[$incompatibleReduction->id])){
						unset($validReductions[$incompatibleReduction->id]);
					}
				}
			}
		}
		return $validReductions;
	}

	/**
	 * Compute the price with the reductions applied
	 * @param  array $participations list of selected activities with the quantities
	 * @return float
	 */
	public function computeFinalPrice($model){
		$participations = $model->participations;
		$reduced_price = 0;
		$validReductions = $this->getValidReductions($model);

		// Filtering the rules on the activityGroup
		$activityGroupsReductionRule = [];
		foreach($validReductions as $reduction){
			foreach($reduction->rules as $rule){
				$activityGroupsReductionRule[$rule->activityGroup->id] = $rule;
			}
		}
		// Applying the price for the activity
		foreach ($participations as $participation) {
			$activity = $participation->activity;
			if(isset($activityGroupsReductionRule[$activity->activityGroup->id])){
				$rule = $activityGroupsReductionRule[$activity->activityGroup->id];
				if($rule->type == ReductionRule::ACTIVITY_PRICE)
					$reduced_price+= $rule->value + $activity->price*($participation->quantity - 1); // one at reduced price and the others at normal price
			}else{
				$reduced_price+= $activity->price*$participation->quantity;
			}
		}
		// Applying the reductions where the reduction has a price for the group of activities
		foreach($validReductions as $reduction){
			foreach($reduction->rules as $rule){
				if($rule->type == ReductionRule::TOTAL_PRICE){
					$reduced_price+=$rule->value + $activity->price*($participation->quantity - 1); // one at reduced price and the others at normal price
				}
			}
		}
		return $reduced_price;
	}

	/**
	 * Compute the total price for a list of activities
	 * @param  array $activities list of selected activities
	 * @return float
	 */
	public function computeUnreducedPrice($participations){
		$total_price = 0;
		foreach ($participations as $participation) {
			$activity = $participation->activity;
			$total_price+= $activity->price*$participation->quantity;
		}
		return $total_price;
	}

}