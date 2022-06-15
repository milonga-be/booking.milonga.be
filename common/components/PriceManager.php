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
	 * @param  array $activities The selected activities
	 * @return boolean
	 */
	public function getValidReductions($activities){
		$reductions = $this->event->reductions;
		$activitiesCounts = [];
		// Counting the activities per group to check if reductions are valid
		foreach($activities as $activity){
			if(!isset($activitiesCounts[$activity->activityGroup->id]))
				$activitiesCounts[$activity->activityGroup->id] = 0;
			$activitiesCounts[$activity->activityGroup->id]++;
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
			}
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
	 * @param  array $activities list of selected activities
	 * @return float
	 */
	public function computeFinalPrice($activities){
		$reduced_price = 0;
		$validReductions = $this->getValidReductions($activities);

		// Filtering the rules on the activityGroup
		$activityGroupsReductionRule = [];
		foreach($validReductions as $reduction){
			foreach($reduction->rules as $rule){
				$activityGroupsReductionRule[$rule->activityGroup->id] = $rule;
			}
		}
		// Applying the price for the activity
		foreach ($activities as $activity) {
			if(isset($activityGroupsReductionRule[$activity->activityGroup->id])){
				$rule = $activityGroupsReductionRule[$activity->activityGroup->id];
				if($rule->type == ReductionRule::ACTIVITY_PRICE)
					$reduced_price+= $rule->value*$activity->getPersonsIncluded();
			}else{
				$reduced_price+= $activity->price*$activity->getPersonsIncluded();
			}
		}
		// Applying the reductions where the reduction has a price for the group of activities
		foreach($validReductions as $reduction){
			foreach($reduction->rules as $rule){
				if($rule->type == ReductionRule::TOTAL_PRICE){
					$reduced_price+=$rule->value*$activity->getPersonsIncluded();
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
	public function computeUnreducedPrice($activities){
		$total_price = 0;
		foreach ($activities as $activity) {
			$total_price+= $activity->price*$activity->getPersonsIncluded();
		}
		return $total_price;
	}

}