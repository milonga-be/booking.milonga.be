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
class Activity extends ActiveRecord
{
	public static function tableName()
    {
        return 'activity';
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
            [['title', 'datetime', 'price', 'activity_group_id', 'teacher_id'], 'safe'],
            [['title'], 'required'],
            [['couple_activity'], 'in', 'range' => [0, 1]],
            [['max_participants'], 'integer'],
        ];
    }

    public function attributeLabels(){
        return [
            'title' => Yii::t('booking', 'Title'),
            'datetime' => Yii::t('booking', 'Date'),
            'price' => Yii::t('booking', 'Price'),
            'activity_group_id' => Yii::t('booking', 'Type'),
            'teacher_id' => Yii::t('booking', 'Teachers'),
            'couple_activity' => Yii::t('booking', 'Couple Activity'),
        ];
    }

    /**
     * Describe the relation between a Activity and its participations
     * @return ActiveQuery
     */
    public function getParticipations(){
        return $this->hasMany(Participation::className(), ['activity_id' => 'id'])->joinWith('booking')->where(['booking.confirmed' => 1]);
    }

    /**
     * Get the datetime as an object
     * @return Datetime
     */
    public function getDatetimeObject(){
        return new \Datetime($this->datetime);
    }

    public function beforeDelete(){
        if (!parent::beforeDelete()) {
            return false;
        }
        if(sizeof($this->participations)){
            foreach ($this->participations as $participation) {
                if(!$participation->delete()){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Describe the relation between an activity and its event
     * @return ActiveQuery
     */
    public function getEvent(){
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * Describe the relation between an activity and its teachers
     * @return ActiveQuery
     */
    public function getTeacher(){
        return $this->hasOne(Teacher::className(), ['id' => 'teacher_id']);
    }

    /**
     * A summary : teacher + title
     * @return string
     */
    public function getSummary(){
        $summary = '';
        if(isset($this->teacher)){
            $summary.=$this->teacher->name.' : ';
        }
        $summary.=$this->title;
        return $summary;
    }

    /**
     * Describe the relation between an activity and its activity group
     * @return ActiveQuery
     */
    public function getActivityGroup(){
        return $this->hasOne(ActivityGroup::className(), ['id' => 'activity_group_id']);
    }
}