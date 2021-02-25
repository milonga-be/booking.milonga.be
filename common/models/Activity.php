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
            [['title', 'datetime', 'price'], 'safe'],
            [['title'], 'required'],
            [['couple_activity'], 'in', 'range' => [0, 1]],
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
}