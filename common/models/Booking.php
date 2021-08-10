<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * Login form
 */
class Booking extends ActiveRecord
{
	public static function tableName()
    {
        return 'booking';
    }

    public function behaviors()
    {
        return [
            [
                'class' => UTCDatetimeBehavior::class,
            ],
            [
                'class' => UUIDBehavior::class,
                'column' => 'uuid'
            ],
        ];
    }

    public function rules(){
        return [
            [['firstname', 'lastname', 'email'], 'required'],
            [['total_price'], 'number'],
        ];
    }

    /**
     * Describe the relation between a Booking and its participations
     * @return ActiveQuery
     */
    public function getPayments(){
        return $this->hasMany(Payment::className(), ['booking_id' => 'id']);
    }

    /**
     * Describe the relation between a Booking and its participations
     * @return ActiveQuery
     */
    public function getParticipations(){
        return $this->hasMany(Participation::className(), ['booking_id' => 'id']);
    }

    /**
     * Describe the relation between a Booking and its activities
     * @return ActiveQuery
     */
    public function getActivities(){
        return $this->hasMany(Activity::className(), ['id' => 'activity_id'])->via('participations');
    }

    /**
     * Returns the activityGroups for which the booking has an activity
     * @return ActiveQuery
     */
    public function getActivityGroups(){
        return ActivityGroup::find()->joinWith('activities')->where(['IN', 'activity.id', ArrayHelper::getColumn($this->activities, 'id')])->all();
    }

    /**
     * Describe the relation between a booking and its event
     * @return ActiveQuery
     */
    public function getEvent(){
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    public function beforeDelete(){
        if (!parent::beforeDelete()) {
            return false;
        }
        if(sizeof($this->participations)){
            foreach ($this->participations as $participation) {
                if(!$participation->delete()){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Readable name for the booker
     * @return string
     */
    public function getName(){
        $name = '';
        if($this->firstname){
            $name.=$this->firstname.' ';
        }
        if($this->lastname){
            $name.=$this->lastname;
        }
        return $name;
    }

    /**
     * Get the list of activities for a booking
     * @return array
     */
    public function getActivitiesList(){
        return ArrayHelper::map(Activity::find()->where(['event_id' => $this->event_id])->andWhere(['NOT IN', 'activity.id', ArrayHelper::getColumn($this->activities, 'id')])->asArray()->all() , 'uuid', 'title');
    }

    /**
     * Get the amount already paid
     */
    public function getPaid(){
        $paid = 0;
        $payments = $this->payments;
        foreach($payments as $payment){
            $paid+= $payment->amount;
        }
        return $paid;
    }
}