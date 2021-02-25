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
class ActivityGroup extends ActiveRecord
{
	public static function tableName()
    {
        return 'activity_group';
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
     * Describe the relation between a Activity and its group
     * @return ActiveQuery
     */
    public function getActivities(){
        return $this->hasMany(Activity::className(), ['activity_group_id' => 'id'])->orderBy('datetime');
    }

    /**
     * Returns the activities for a group in an associative array, where activites of a same day are collected together 
     * @return array
     */
    public function getActivitiesByDates(){
        $activities = $this->activities;
        $activities_by_dates = array();
        foreach ($activities as $activity) {
            $activities_by_dates[substr($activity->datetime,0,10)][] = $activity;
        }
        return $activities_by_dates;
    }
}