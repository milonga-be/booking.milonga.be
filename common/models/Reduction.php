<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;


class Reduction extends ActiveRecord
{

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

    /**
     * Describe the relation between an reduction and its incompatible reductions
     * @return ActiveQuery
     */
    public function getIncompatibleReductions(){
        return $this->hasMany(Reduction::className(), ['id' => 'incompatible_reduction_id'])->viaTable('reduction_incompatibility', ['reduction_id' => 'id']);
    }

    /**
     * Returns a complete summary of the reduction (all rules)
     * @return string
     */
    public function getSummary(){
        $summaries = [];
        foreach($this->rules as $rule){
            $summaries[] = $rule->getSummary();
        }
        return implode('+', $summaries);
    }
}