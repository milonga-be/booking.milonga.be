<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * Login form
 */
class Booking extends ActiveRecord
{
	public static function tableName()
    {
        return 'booking';
    }

    public function rules(){
        return [
            [['total_price', 'firstname', 'lastname', 'email'], 'required'],
        ];
    }

    /**
     * Describe the relation between a Activity and its group
     * @return ActiveQuery
     */
    public function getParticipations(){
        return $this->hasMany(Participation::className(), ['id' => 'participation_id'])
            ->viaTable('booking_participations', ['booking_id' => 'id']);
    }

    /**
     * Generates a unique id for communication with the client
     * @return boolean
     */
    public function beforeSave($insert){
    if(parent::beforeSave($insert)){

        if($this->isNewRecord){
            $string = openssl_random_pseudo_bytes(4);
            $this->uuid = strtoupper(strtr(base64_encode($string), '+/=', 'ABC'));
        }
        return true;

    }else{
        return false;
    }

}
}