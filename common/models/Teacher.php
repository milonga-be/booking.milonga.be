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
class Teacher extends ActiveRecord
{
	public static function tableName()
    {
        return 'teacher';
    }

    public function rules(){
        return [
            [['name'], 'required'],
        ];
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
        return $this->hasMany(Activity::className(), ['teacher_id' => 'id'])->orderBy('datetime');
    }

    /**
     * Describe the relation between a teacher and its event
     * @return ActiveQuery
     */
    public function getEvent(){
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }
}