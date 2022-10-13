<?php
namespace common\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\components\UTCDatetimeBehavior;
use mootensai\behaviors\UUIDBehavior;
use common\components\PriceManager;

/**
 * Login form
 */
class Booking extends ActiveRecord
{
	public static function tableName()
    {
        return 'booking';
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

    public function rules(){
        return [
            [['firstname', 'lastname', 'email'], 'required'],
            [['total_price'], 'number'],
        ];
    }

    /**
     * Describe the relation between a Booking and its participations
     * @return ActiveQuery
     */
    public function getPayments(){
        return $this->hasMany(Payment::className(), ['booking_id' => 'id']);
    }

    /**
     * Describe the relation between a Booking and its participations
     * @return ActiveQuery
     */
    public function getParticipations(){
        return $this->hasMany(Participation::className(), ['booking_id' => 'id']);
    }

    /**
     * Describe the relation between a Booking and its activities
     * @return ActiveQuery
     */
    public function getActivities(){
        return $this->hasMany(Activity::className(), ['id' => 'activity_id'])->via('participations');
    }

    /**
     * Returns the activityGroups for which the booking has an activity
     * @return ActiveQuery
     */
    public function getActivityGroups(){
        return ActivityGroup::find()->joinWith('activities')->where(['IN', 'activity.id', ArrayHelper::getColumn($this->activities, 'id')])->all();
    }

    /**
     * Describe the relation between a booking and its event
     * @return ActiveQuery
     */
    public function getEvent(){
        return $this->hasOne(Event::className(), ['id' => 'event_id']);
    }

    /**
     * Describe the relation between a booking and its partner booking
     * @return ActiveQuery
     */
    public function getPartnerBooking(){
        return $this->hasOne(Booking::className(), ['id' => 'partner_booking_id']);
    }

    public function beforeDelete(){
        if (!parent::beforeDelete()) {
            return false;
        }
        if(sizeof($this->participations)){
            foreach ($this->participations as $participation) {
                if(!$participation->delete()){
                    return false;
                }
            }
        }
        if(sizeof($this->payments)){
            foreach ($this->payments as $payment) {
                if(!$payment->delete()){
                    return false;
                }
            }
        }
        return true;
    }

    /**
     * Recompute and save the final price
     */
    public function saveFinalPrice(){
        $priceManager = new PriceManager($this->event);
        $this->total_price = $priceManager->computeFinalPrice($this->participations);
        return $this->save();
    }

    /**
     * Send an email to the booker with the summary of the booking
     * @return boolean
     */
    public function sendEmailSummary(){
        $priceManager = new PriceManager($this->event);
        return Yii::$app->mailer->compose('@common/mail/booking-confirmed', ['booking' => $this, 'priceManager' => $priceManager])
                    ->setFrom(Yii::$app->params['publicEmail'])
                    ->setTo($this->email)
                    ->setBcc(Yii::$app->params['publicEmail'])
                    ->setSubject(Yii::t('booking', 'Invoice BTF {ref}', ['ref' => $this->reference]))
                    ->send();
    }

    /**
     * Send the cancelling email
     */
    public function sendEmailCancelled(){
        return Yii::$app->mailer->compose('@common/mail/booking-cancelled', ['booking' => $this])
                    ->setFrom(Yii::$app->params['publicEmail'])
                    ->setTo($this->email)
                    ->setBcc(Yii::$app->params['publicEmail'])
                    ->setSubject(Yii::t('booking', 'Reservation Cancelled'))
                    ->send();
    }

    /**
     * Send an email when payment is completed
     */
    public function sendEmailPaymentComplete(){
        return Yii::$app->mailer->compose('@common/mail/booking-payment', ['booking' => $this])
                    ->setFrom(Yii::$app->params['publicEmail'])
                    ->setTo($this->email)
                    ->setBcc(Yii::$app->params['publicEmail'])
                    ->setSubject(Yii::t('booking', 'Payment Complete'))
                    ->send();
    }

    /**
     * Send an email to remind to pay
     */
    public function sendEmailPaymentReminder(){
        return Yii::$app->mailer->compose('@common/mail/booking-payment-reminder', ['booking' => $this])
                    ->setFrom(Yii::$app->params['publicEmail'])
                    ->setTo($this->email)
                    ->setBcc(Yii::$app->params['publicEmail'])
                    ->setSubject(Yii::t('booking', 'Payment Reminder'))
                    ->send();
    }

    /**
     * Readable name for the booker
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

    /**
     * Readable reference for the booker
     * @return string
     */
    public function getReference(){
        return str_pad($this->id, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Get the list of activities for a booking
     * @return array
     */
    public function getActivitiesList(){
        $workshops = Activity::find()->where(['event_id' => $this->event_id])->andWhere(['NOT IN', 'activity.id', ArrayHelper::getColumn($this->activities, 'id')])->andWhere(['=', 'activity.couple_activity', 1])->asArray()->all();
        $others = Activity::find()->where(['event_id' => $this->event_id])->andWhere(['=', 'activity.couple_activity', 0])->asArray()->all();
        return ArrayHelper::map( array_merge($workshops, $others), 'uuid', 'title');
    }

    /**
     * Get the amount already paid
     */
    public function computeTotalPaid(){
        $paid = 0;
        $payments = $this->payments;
        foreach($payments as $payment){
            $paid+= $payment->amount;
        }
        return $paid;
    }

    /**
     * Is the payment complete
     * @return boolean
     */
    public function isPaymentComplete(){
        return ($this->total_paid >= $this->total_price);
    }

    /**
     * Get a payment status : paid / total price
     */
    public function getPaymentStatus(){
        return $this->total_paid.'/'.(int)$this->total_price.'€';
    }

    /**
     * Get the amount still to pay
     * @return float
     */
    public function getAmountDue(){
        return $this->total_price - $this->total_paid;
    }
}