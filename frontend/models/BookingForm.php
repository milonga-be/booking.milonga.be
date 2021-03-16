<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class BookingForm extends Model
{
	var $activities = array();

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // name, email, subject and body are required
            [['activities'], 'required'],
            [['activities'], 'each', 'rule' => ['string']],
        ];
    }
}