<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use common\components\PriceManager;
use mootensai\behaviors\UUIDBehavior;

/**
 * Login form
 */
class Participation extends ActiveRecord
{
	public static function tableName()
    {
        return 'participation';
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
            [['activity_id', 'booking_id'], 'required'],
            [['activity_id', 'booking_id'], 'integer'],
        ];
    }

    /**
     * Clean the database before delete
     */
    public function beforeDelete(){
        if (!parent::beforeDelete()) {
            return false;
        }
        if(isset($this->partner)){
            if(!$this->partner->delete())
                return false;
        }
        return true;
    }

    /**
     * Re-compute the price after adding an activity
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if($insert && !is_null($this->booking)){
            $booking = $this->booking;
            $booking->total_price = PriceManager::computeTotalPrice($booking->activities);
            $booking->save();
        }
    }

    /**
     * Describe the relation between a Participation and its Activity
     * @return ActiveQuery
     */
    public function getActivity(){
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }

    /**
     * Describe the relation between a Participation and its Booking
     * @return ActiveQuery
     */
    public function getBooking(){
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }

    /**
     * Describe the relation between a Participation and its Partner
     * @return ActiveQuery
     */
    public function getPartner(){
        return $this->hasOne(Partner::className(), ['participation_id' => 'id']);
    }
}