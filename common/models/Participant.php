<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Login form
 */
class Participant extends ActiveRecord
{
	public static function tableName()
    {
        return 'participant';
    }

    public function rules(){
        return [
            [['firstname', 'lastname','email'], 'required'],
            [['email'], 'email'],
        ];
    }

    public function attributeLabels(){
        return [
            'firstname' => Yii::t('booking', 'Firstname'),
            'lastname' => Yii::t('booking', 'Lastname'),
            'email' => Yii::t('booking', 'Email'),
            'phone' => Yii::t('booking', 'Phone'),
        ];
    }
}