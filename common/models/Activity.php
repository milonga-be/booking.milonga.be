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
            [['title', 'datetime', 'price', 'activity_group_id', 'teacher_id', 'dance', 'level'], 'safe'],
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
            'readableDance' => Yii::t('booking', 'Dance'),
            'readableLevel' => Yii::t('booking', 'Level'),
        ];
    }

    /**
     * Describe the relation between a Activity and its participations
     * @return ActiveQuery
     */
    public function getParticipations(){
        return $this->hasMany(Participation::className(), ['activity_id' => 'id']);
    }

    /**
     * Describe the relation between a Activity and its participations (only the confirmed one)
     * @return ActiveQuery
     */
    public function getConfirmedParticipations(){
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
    public function getSummary($limit = 30){
        $summary = '';
        if(isset($this->teacher)){
            $summary.=$this->teacher->name;
        }
        if(isset($this->dance)){
            $summary.=' ('.$this->danceList[$this->dance].')';
        }
        if(isset($this->teacher)){
            $summary.=' ';
        }
        $summary.=$this->title;
        if(strlen($summary) > $limit){
            $summary = substr($summary, 0, $limit).'...';
        }
        return $summary;
    }

    /**
     * Describe the relation between an activity and its activity group
     * @return ActiveQuery
     */
    public function getActivityGroup(){
        return $this->hasOne(ActivityGroup::className(), ['id' => 'activity_group_id']);
    }

    /**
     * Get the list of dances
     * @return array
     */
    public function getDanceList(){
        return [
            '' => '--',
            'tango' => 'Tango',
            'vals' => 'Vals',
            'milonga' => 'Milonga'
        ];
    }

    /**
     * Get a human readable dance
     * @return string
     */
    public function getReadableDance(){
        if(!empty($this->dance))
        return $this->danceList[$this->dance];
        return null;
    }

    /**
     * Get the list of levels
     * @return array
     */
    public function getLevelList(){
        return [
            '' => '--',
            'beginner' => 'Beginners',
            'intermediate' => 'Intermediates',
            'int-adv' => 'Inter-Adv',
            'advanced' => 'Advanced',
            'all' => 'All Levels'
        ];
    }

    /**
     * Get a human readable level
     * @return string
     */
    public function getReadableLevel(){
        if(!empty($this->level))
        return $this->levelList[$this->level];
        return null;
    }

    /**
     * Get the number of persons included for one booking
     * @return integer 
     */
    public function getPersonsIncluded(){
        if($this->couple_activity)
            return 2;
        else
            return 1;
    }

    /**
     * A summary of the price with the number of times the price is counted (for workshops)
     * @return string
     */
    public function getPriceSummary(){
        if($this->getPersonsIncluded() > 1){
            return $this->getPersonsIncluded().' x '.Yii::$app->formatter->asCurrency($this->price);
        }else{
            return Yii::$app->formatter->asCurrency($this->price);
        }
    }

    /**
     * Get the number of participants
     */
    public function countParticipants(){
        return sizeof($this->participations)*$this->getPersonsIncluded();
    }

    /**
     * Checks that an activity has reached its max number of participants
     */
    public function isFull(){
        if(isset($this->max_participants)){
            return ($this->countParticipants() >= $this->max_participants);
        }else{
            return false;
        }
    }
}