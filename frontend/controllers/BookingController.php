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
					$participation->role = $model->role;
					$participation->has_partner = ($model->has_partner == 'yes');
					$participation->save();
					if($activity->couple_activity == 1 && $model->has_partner == 'yes'){
						$partner = new Partner();
						$partner->firstname = $model->partner_firstname;
						$partner->lastname = $model->partner_lastname;
						$partner->participation_id = $participation->id;
						if($model->role == 'leader')
							$partner->role = 'follower';
						else if($model->role == 'follower')
							$partner->role = 'leader';
						$partner->save();
					}
				}
				
				Yii::$app->mailer->compose('@common/mail/booking-confirmed', ['booking' => $booking, 'priceManager' => $priceManager])
		            ->setFrom('booking@brusselstangofestival.be')
		            ->setTo($booking->email)
		            ->setBcc('info@brusselstangofestival.be')
		            ->setSubject('New Reservation Confirmed')
		            ->send();
				return $this->redirect(['booking/confirmed', 'uuid' => $booking->uuid, 'priceManager' => $priceManager]);
			}
			
		}
		return $this->render('summary', ['model' => $model, 'event' => $event, 'priceManager' => $priceManager]);
	}

	public function actionConfirmed($uuid){
		$booking = Booking::findOne(['uuid' => $uuid]);
		$priceManager = new PriceManager($booking->event);
		if(!$booking){
			throw new NotFoundHttpException('No booking found');
		}
		return $this->render('confirmed', ['model' => $booking, 'event' => $booking->event, 'priceManager' => $priceManager]);
	}

	/**
	 * Find an event
	 * @param  integer $id The event id 
	 * @return Event
	 */
	private function findModel($uuid){
		return Event::findOne(['uuid' => $uuid]);
	}
}