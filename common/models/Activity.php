<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Login form
 */
class Activity extends ActiveRecord
{
	public static function tableName()
    {
        return 'activity';
    }

    /**
     * Describe the relation between a Activity and its participations
     * @return ActiveQuery
     */
    public function getParticipations(){
        return $this->hasMany(Participation::className(), ['activity_id' => 'id']);
    }
}