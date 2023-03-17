<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Booking;
use common\models\Payment;
use common\models\Event;

/**
 * Site controller
 */
class PaymentController extends Controller
{
	/** 
	 * Send payments reminder
	 * @return void
	 */
	public function actionReminder(){
		$datetime = new \Datetime();
		$datetime->modify('-10 days');
		$bookings = Booking::find()->where('total_paid < total_price')->andWhere(['<', 'created_at', $datetime->format('Y-m-d H:i:s')])->andWhere(['confirmed' => 1])->all();
		foreach($bookings as $booking){
			if(!$booking->event->closed)
				$booking->sendEmailPaymentReminder();
		}
	}

	/** 
	 * Send last payment reminder
	 * @return void
	 */
	public function actionLastReminder(){
		// Looking for an event that closes in 3 days
		$start_date_in3_days = new \Datetime();
		$start_date_in3_days->modify('+3 days');
		$closing_event = Event::findOne(['start_date' => $start_date_in3_days->format('Y-m-d')]);
		if($closing_event){
			// Send last reminder
			$bookings = Booking::find()->where(['event_id' => $closing_event->id, 'confirmed' => 1])->andWhere('total_paid < total_price')->all();
			foreach($bookings as $booking){
				$booking->sendEmailPaymentReminder(true);
			}
		}
	}
}