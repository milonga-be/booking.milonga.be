<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Login form
 */
class Participation extends ActiveRecord
{
	public static function tableName()
    {
        return 'participation';
    }

    public function rules(){
        return [
            [['activity_id', 'participant1_id'], 'required'],
            [['activity_id', 'participant1_id','participant2_id'], 'integer'],
        ];
    }

    /**
     * Describe the relation between a Participation and its Activity
     * @return ActiveQuery
     */
    public function getActivity(){
        return $this->hasOne(Activity::className(), ['id' => 'activity_id']);
    }
}