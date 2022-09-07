<?php

namespace backend\models;

use Yii;
use yii\base\Model;
use common\models\Activity;
use common\models\Participation;

/**
 * ContactForm is the model behind the contact form.
 */
class ParticipationForm extends Model
{
    var $role;
    var $has_partner = 'yes';
    var $partner_firstname;
    var $partner_lastname;

    public function rules()
    {
        return [
            [['role', 'has_partner'], 'required'],
            [['partner_lastname', 'partner_firstname'], 'string']
        ];
    }
}