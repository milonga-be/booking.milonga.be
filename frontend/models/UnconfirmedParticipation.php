<?php
namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Activity;

class UnconfirmedParticipation extends Model
{
	var $activity_uuid;
	var $quantity = 1;

	/**
	 * Return the activity referenced by the activity_uuid
	 * @return [type] [description]
	 */
	public function getActivity(){
		return Activity::findOne(['uuid' => $this->activity_uuid]);
	}

	/**
     * A summary of the price with the number of times the price is counted
     */
    public function getPriceSummary(){
        if($this->quantity > 1){
            return $this->quantity.' x '.Yii::$app->formatter->asCurrency($this->activity->price);
        }else{
            return Yii::$app->formatter->asCurrency($this->activity->price);
        }
    }
}