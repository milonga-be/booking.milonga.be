<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;

/**
 * Login form
 */
class Payment extends ActiveRecord
{
	public static function tableName()
    {
        return 'payment';
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
            [['amount'], 'required'],
            [['amount'], 'number'],
        ];
    }

    /**
     * Describe the relation between an payment and its booking
     * @return ActiveQuery
     */
    public function getBooking(){
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }
}