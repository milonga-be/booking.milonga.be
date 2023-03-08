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

    const TYPE_TRANSFER = 'transfer';
    const TYPE_CASH = 'cash';

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
            [['type'], 'in', 'range' => ['transfer', 'cash']],
        ];
    }

    /**
     * Describe the relation between an payment and its booking
     * @return ActiveQuery
     */
    public function getBooking(){
        return $this->hasOne(Booking::className(), ['id' => 'booking_id']);
    }

    /**
     * Get the different types of reduction
     * @return array
     */
    public function getTypesList(){
        return [
            self::TYPE_TRANSFER => Yii::t('booking', 'Transfer'),
            self::TYPE_CASH => Yii::t('booking', 'Cash'),
        ];
    }
}