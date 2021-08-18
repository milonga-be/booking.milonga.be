<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Controller;
use common\models\Event;
use common\models\Participant;
use common\models\Partner;
use common\models\Activity;
use common\models\Reduction;
use common\models\Booking;
use common\models\Participation;
use frontend\models\BookingForm;
use common\components\PriceManager;

/**
 * Site controller
 */
class BookingController extends Controller
{

	/**
	 * The subscription form for the event
	 * @param  string Unique Id of the event
	 * @return mixed
	 */
	public function actionCreate($event_uuid){
		$event = $this->findModel($event_uuid);
		$model = new BookingForm();
		$priceManager = new PriceManager($event);
		if($model->load(Yii::$app->request->post()) && $model->validate()){
			return $this->render('summary', ['model' => $model, 'event' => $event, 'priceManager' => $priceManager]);
		}

		return $this->render('create',['event' => $event, 'model' => $model]);
	}

	/**
	 * The confirmation form for an event and a specific booking
	 * @param  string $event_uuid   Event Unique Id
	 * @return mixed
	 */
	public function actionSummary($event_uuid){
		$event = $this->findModel($event_uuid);
		$model = new BookingForm();
		$model->setScenario(BookingForm::SCENARIO_CONFIRMATION);
		$priceManager = new PriceManager($event);

		if($model->load(Yii::$app->request->post()) && $model->validate()){
			// Creating the booking
			$booking = new Booking();
			$booking->event_id = $event->id;
			$booking->firstname = $model->firstname;
			$booking->lastname = $model->lastname;
			$booking->email = $model->email;
			$booking->confirmed = 1;
			if($booking->save()){
				// Adding the selected activities
				foreach($model->activities as $activity){
					$participation = new Participation();
					$participation->activity_id = $activity->id;
					$participation->booking_id = $booking->id;
					$participation->save();
					if($activity->couple_activity == 1){
						$partner = new Partner();
						$partner->firstname = $model->partner_firstname;
						$partner->lastname = $model->partner_lastname;
						$partner->participation_id = $participation->id;
						$partner->save();
					}
				}
				
				Yii::$app->mailer->compose('@common/mail/booking-confirmed', ['booking' => $booking, 'priceManager' => $priceManager])
		            ->setFrom('booking@brusselstangofestival.be')
		            ->setTo($booking->email)
		            ->setBcc('info@brusselstangofestival.be')
		            ->setSubject('New Reservation Confirmed')
		            ->send();
				return $this->redirect(['booking/confirmed', 'uuid' => $booking->uuid]);
			}
			
		}
		return $this->render('summary', ['model' => $model, 'event' => $event, 'priceManager' => $priceManager]);
	}

	public function actionConfirmed($uuid){
		$booking = Booking::findOne(['uuid' => $uuid]);
		if(!$booking){
			throw new NotFoundHttpException('No booking found');
		}
		return $this->render('confirmed', ['booking' => $booking, 'event' => $booking->event]);
	}

	/**
	 * Find an event
	 * @param  integer $id The event id 
	 * @return Event
	 */
	private function findModel($uuid){
		return Event::findOne(['uuid' => $uuid]);
	}

	/**
	 * Compute total price for all activities selected
	 * @param  array $activities ids of the activities
	 * @return double
	 */
	private function computeTotalPrice($event_id, $activity_ids){
		$total_price = 0;
		$counts_per_group = array();
		$count = 0;
		$event = Event::findOne($event_id);
		$activity_ids = array_filter($activity_ids);

		// Finding the reduction that apply on all the activity
		$count = sizeof($activity_ids);
		$reduce_every_price_reductions = $event->getAppliedReductions($count, Reduction::REDUCE_EVERY_PRICE);

		foreach ($activity_ids as $activity_id) {
			if($activity_id){
				$activity = Activity::findOne($activity_id);
				$total_price+= $activity->price;
				foreach ($reduce_every_price_reductions as $reduction) {
					$total_price-=$reduction->value;
				}
			}
		}
		// Applying the percentag reductions 
		$pct_reductions = $event->getAppliedReductions($count, Reduction::PCT_WHOLE_PRICE);
		
		foreach ($pct_reductions as $reduction) {
			$total_price = (100 - $reduction->value) * $total_price / 100;
		}

		return round($total_price, 2, PHP_ROUND_HALF_DOWN);
	}

	/**
	 * Compute the total price, with the reductions
	 * @return array
	 */
	public function actionComputePrice($event_id, $activities){
		return $this->computeTotalPrice($event_id, explode(',',$activities)).' €';
	}
}