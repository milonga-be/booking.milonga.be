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
        return 'partner';
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
        ];
    }

    /**
     * Readable name for the participant
     * @return string
     */
    public function getName(){
        $name = '';
        if($this->firstname){
            $name.=$this->firstname.' ';
        }
        if($this->lastname){
            $name.=$this->lastname;
        }
        return $name;
    }
}