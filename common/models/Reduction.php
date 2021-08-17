<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;


class Reduction extends ActiveRecord
{

	// const PCT_WHOLE_PRICE = 'percentage_of_whole_price';
	// const REDUCE_EVERY_PRICE = 'reduce_every_price';

	public static function tableName()
    {
        return 'reduction';
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
            [['name'], 'required'],
            [['validity_start', 'validity_end'], 'datetime', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * Describe the relation between a teacher and its event
     * @return ActiveQuery
     */
    public function getEvent(){
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * Describe the relation between an reduction and its rules
     * @return ActiveQuery
     */
    public function getRules(){
        return $this->hasMany(ReductionRule::className(), ['reduction_id' => 'id']);
    }
}