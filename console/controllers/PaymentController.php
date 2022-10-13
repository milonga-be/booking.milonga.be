<?php
namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\models\Booking;
use common\models\Payment;

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
		$bookings = Booking::find()->where('total_paid < total_price')->all();
		foreach($bookings as $booking){
			$booking->sendEmailPaymentReminder();
		}
	}
}