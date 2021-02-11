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
class Event extends ActiveRecord
{
	public static function tableName()
    {
        return 'event';
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

    /**
     * Describe the relation between a Weekday and its absence
     * @return ActiveQuery
     */
    public function getActivityGroups(){
        return $this->hasMany(ActivityGroup::className(), ['event_id' => 'id']);
    }

    /**
     * Get the reductions applied
     * @return array
     */
    public function getAppliedReductions($count, $type){
        $reductions = Reduction::find()->where(['event_id' => $this->id])
            ->andWhere(['type' => $type])
            ->andWhere([
                'OR',
                    ['AND', /* valid for a certain number of activities */
                        ['<=', 'lower_limit', $count],
                        ['>=', 'higher_limit', $count],
                    ],
                    ['AND', /* always valid */
                        ['IS', 'lower_limit', null],
                        ['IS', 'higher_limit', null],
                    ],
                ])
            ->andWhere([
                'OR',
                    ['AND', /* always valid */
                        ['IS', 'validity_start', null],
                        ['IS', 'validity_end', null],
                    ],
                    ['AND', /* valid only a certain period of time */
                        ['<=', 'validity_start', date('Y-m-d')],
                        ['>=', 'validity_end', date('Y-m-d')]
                    ]
            ])->all();
        return $reductions;                
    }

    /**
     * Get the reductions applied
     * @return array
     */
    public function getReductions(){
        return Reduction::find()->where(['event_id' => $this->id])
            ->andWhere([
                'OR',
                    ['AND', /* always valid */
                        ['IS', 'validity_start', null],
                        ['IS', 'validity_end', null],
                    ],
                    ['AND', /* valid only a certain period of time */
                        ['<=', 'validity_start', date('Y-m-d')],
                        ['>=', 'validity_end', date('Y-m-d')]
                    ]
            ])->all();             
    }

}