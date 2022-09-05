<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use common\models\Activity;

/**
 * ContactForm is the model behind the contact form.
 */
class BookingForm extends Model
{
	var $activities_uuids = array();

    var $firstname;
    var $lastname;
    var $email;
    var $role;
    var $has_partner = 'yes';

    var $partner_firstname;
    var $partner_lastname;

    // const SCENARIO_REGISTRATION = 'registration';
    const SCENARIO_CONFIRMATION = 'confirmation';

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['activities_uuids'], 'required'],
            [['firstname', 'lastname', 'email', 'role'], 'required', 'on' => self::SCENARIO_CONFIRMATION],
            [[/*'partner_firstname', 'partner_lastname', */'has_partner'], 'required', 'when' => function($attribute, $params){
                return $this->enablePartnerForm();
            }],
            [['activities_uuids'], 'each', 'rule' => ['string']],
            [['firstname', 'lastname', 'role', 'partner_firstname', 'partner_lastname'], 'string'],
            [['email'], 'email'],
            [['role'], 'in', 'range' => ['leader', 'follower']],
            [['has_partner'], 'in', 'range' => ['yes', 'no']],
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
            self::SCENARIO_DEFAULT => ['activities_uuids'],
            self::SCENARIO_CONFIRMATION => ['activities_uuids', 'firstname', 'lastname', 'email', 'partner_firstname', 'partner_lastname', 'role'],
        ];
    }

    /**
     * Returns a list of activities based on the activities uuids 
     * @return array
     */
    public function getActivities(){
        $activities = array();
        foreach ($this->activities_uuids as $activity_uuid) {
            if($activity_uuid){
                $activities[] = Activity::findOne(['uuid' => $activity_uuid]);
            }
        }
        return $activities;
    }

    /**
     * Check if we need partner data
     * @return boolean
     */
    public function enablePartnerForm(){
        foreach($this->activities as $activity){
            if($activity->couple_activity){
                return true;
            }
        }
        return false;
    }
}