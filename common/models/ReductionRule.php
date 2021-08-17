<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;


class ReductionRule extends ActiveRecord
{

	const TOTAL_PRICE = 'total_price';
    const ACTIVITY_PRICE = 'activity_price';

	public static function tableName()
    {
        return 'reduction_rule';
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
            [['value', 'type', 'lower_limit', 'higher_limit', 'activity_group_id'], 'required'],
        ];
    }

    /**
     * Describe the relation between a rule and its reduction
     * @return ActiveQuery
     */
    public function getReduction(){
        return $this->hasOne(Reduction::className(), ['id' => 'reduction_id']);
    }

    /**
     * Get the different types of reduction
     * @return array
     */
    public function getTypesList(){
        return [
            self::TOTAL_PRICE => 'Total Price',
            self::ACTIVITY_PRICE => 'Activity Price',
        ];
    }

    /**
     * Describe the relation between a reduction rule and its activity group
     * @return ActiveQuery
     */
    public function getActivityGroup(){
        return $this->hasOne(ActivityGroup::className(), ['id' => 'activity_group_id']);
    }
}