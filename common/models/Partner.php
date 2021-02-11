<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Login form
 */
class Partner extends ActiveRecord
{
	public static function tableName()
    {
        return 'participant';
    }

    public function rules(){
        return [
            [['firstname', 'lastname'], 'required'],
        ];
    }

    public function attributeLabels(){
        return [
            'firstname' => Yii::t('booking', 'Firstname'),
            'lastname' => Yii::t('booking', 'Lastname'),
            'email' => Yii::t('booking', 'Email'),
        ];
    }
}