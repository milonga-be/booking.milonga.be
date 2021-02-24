<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;
use common\models\Participation;

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
            [['firstname', 'lastname'], 'required'],
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

    public function attributeLabels(){
        return [
            'firstname' => Yii::t('booking', 'Firstname'),
            'lastname' => Yii::t('booking', 'Lastname'),
        ];
    }

    /**
     * Describe the relation between a Participation and its Partner
     * @return ActiveQuery
     */
    public function getParticipation(){
        return $this->hasOne(Participation::className(), ['partner_id' => 'id']);
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