<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Activity;
use frontend\models\UnconfirmedParticipation;

/**
 * ContactForm is the model behind the contact form.
 */
class BookingForm extends Model
{
    var $activities = array();
    var $promocode;

    var $firstname;
    var $lastname;
    var $email;
    var $role;
    var $has_partner = 'yes';

    var $partner_firstname;
    var $partner_lastname;
    var $partner_email;

    // const SCENARIO_REGISTRATION = 'registration';
    const SCENARIO_CONFIRMATION = 'confirmation';

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['activities'], 'required'],
            [['firstname', 'lastname', 'email', 'role'], 'required', 'on' => self::SCENARIO_CONFIRMATION],
            [['has_partner'], 'required', 
                'when' => 
                    function($attribute, $params){
                        return $this->enablePartnerForm();
                    },
                'on' => self::SCENARIO_CONFIRMATION
            ],
            [['partner_firstname', 'partner_lastname', 'partner_email'], 'required', 
                'when' => 
                    function($attribute, $params){
                        return ($this->has_partner == 'yes' && $this->enablePartnerForm());
                    },
                'on' => self::SCENARIO_CONFIRMATION
            ],
            // [['activities'], 'each', 'rule' => ['integer']],
            [['firstname', 'lastname', 'role', 'partner_firstname', 'partner_lastname'], 'string'],
            [['email', 'partner_email'], 'email'],
            [['role'], 'in', 'range' => ['leader', 'follower']],
            [['has_partner'], 'in', 'range' => ['yes', 'no']],
            [['promocode'], 'string'],
        ];
    }

    public function attributeLabels(){
        return [
            'has_partner' => Yii::t('booking', 'I have a partner')
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios(){
        return [
            self::SCENARIO_DEFAULT => ['activities', 'promocode'],
            self::SCENARIO_CONFIRMATION => ['activities', 'firstname', 'lastname', 'email', 'role', 'has_partner', 'partner_lastname', 'partner_firstname', 'partner_email', 'promocode'],
        ];
    }

    /**
     * Returns a list of activities based on the activities uuids 
     * @return array
     */
    /*public function getActivities(){
        $activities = array();
        foreach ($this->activities as $activity_uuid => $quantity) {
            if($activity_uuid && $quantity){
                $activities[] = Activity::findOne(['uuid' => $activity_uuid]);
            }
        }
        return $activities;
    }*/

    /**
     * Returns a list of unconfirmed participations based on the activities uuids and the quantities
     * @return array
     */
    public function getParticipations(){
        $participations = array();
        foreach ($this->activities as $activity_uuid => $quantity) {
            if($activity_uuid && $quantity > 0){
                $participation = new UnconfirmedParticipation();
                $participation->activity_uuid = $activity_uuid;
                $participation->quantity = $quantity;
                $participations[] = $participation;
            }
        }
        return $participations;
    }

    /**
     * Check if we need partner data
     * @return boolean
     */
    public function enablePartnerForm(){
        /*foreach($this->activities as $activity){
            if($activity->couple_activity){
                return true;
            }
        }*/
        return false;
    }
}