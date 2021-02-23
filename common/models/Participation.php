<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Login form
 */
class Participation extends ActiveRecord
{
	public static function tableName()
    {
        return 'participation';
    }

    public function rules(){
        return [
            [['activity_id', 'booking_id'], 'required'],
            [['activity_id', 'booking_id','partner_id'], 'integer'],
        ];
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
        return $this->hasOne(Partner::className(), ['id' => 'partner_id']);
    }
}