<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;

class Event extends ActiveRecord
{

    const DEFAULT_ACTIVITY_GROUPS = array('Workshop', 'Salon', 'Pass');

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
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id'], 'default', 'value' => function($model, $attribute) {
                $user = Yii::$app->user->identity;
                if($user)
                    return $user->id;
                return null;
            }],
            [['uuid'], 'safe'],
            [['title'], 'string'],
            [['start_date', 'end_date'], 'datetime', 'format' => 'php:Y-m-d'],
        ];
    }

    /**
     * Describe the relation between an event and its activityGroups
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
     * Before delete : delete all activities and reservations
     * @return boolean
     */
    public function beforeDelete(){
        if (!parent::beforeDelete()) {
            return false;
        }
        if(sizeof($this->activities)){
            foreach ($this->activities as $activity) {
                if(!$activity->delete()){
                    return false;
                }
            }
        }
        if(sizeof($this->bookings)){
            foreach ($this->bookings as $booking) {
                if(!$booking->delete()){
                    return false;
                }
            }
        }
        if(sizeof($this->activityGroups)){
            foreach ($this->activityGroups as $activityGroup) {
                if(!$activityGroup->delete()){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * After save : create the standard activity groups
     * @return void
     */
    public function afterSave($insert, $changedAttributes){
        parent::afterSave($insert, $changedAttributes);
        if ($insert) {
            foreach (self::DEFAULT_ACTIVITY_GROUPS as $title) {
                $activityGroup = new ActivityGroup();
                $activityGroup->title = $title;
                $activityGroup->event_id = $this->id;
                $activityGroup->save();
            }
        }
    }

    /**
     * Describe the relation between an event and its activities
     * @return ActiveQuery
     */
    public function getActivities(){
        return $this->hasMany(Activity::className(), ['event_id' => 'id']);
    }

    /**
     * Describe the relation between an event and its teachers
     * @return ActiveQuery
     */
    public function getTeachers(){
        return $this->hasMany(Teacher::className(), ['event_id' => 'id']);
    }

    /**
     * Describe the relation between an event and its bookings
     * @return ActiveQuery
     */
    public function getBookings(){
        return $this->hasMany(Booking::className(), ['event_id' => 'id']);
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