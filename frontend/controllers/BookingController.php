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
use common\models\Role;
use frontend\models\BookingForm;
use common\components\PriceManager;

/**
 * Site controller
 */
class BookingController extends Controller
{

	/**
	 * Shows the booking form for the next event
	 * @return mixed
	 */
	public function actionIndex(){
		$event = Event::find()->orderBy('start_date DESC')->one();
		return $this->actionCreate($event->uuid);
	}

	/**
	 * The subscription form for the event
	 * @param  string Unique Id of the event
	 * @return mixed
	 */
	public function actionCreate($event_uuid){
		$event = $this->findModel($event_uuid);
		$model = new BookingForm();
		$priceManager = new PriceManager($event);
		$post = Yii::$app->request->post();
		// Checking that an activity is selected
		$selected_activites = false;
		if(isset($post['BookingForm']['activities_with_quantities'])){
			foreach($post['BookingForm']['activities_with_quantities'] as $activity_config){
				list($activity_uuid, $quantity) = explode(':', $activity_config);
				if($quantity > 0){
					$selected_activites = true;
					break;
				}
			}
		}
		if(isset($post['BookingForm']['activities_uuids'])){
			foreach($post['BookingForm']['activities_uuids'] as $value){
				if($value){
					$selected_activites = true;
					break;
				}
			}
		}
		if($selected_activites && $model->load($post) && $model->validate()){
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

			// Creating the booking for the main person
			$booking = new Booking();
			$booking->event_id = $event->id;
			$booking->firstname = $model->firstname;
			$booking->lastname = $model->lastname;
			$booking->email = $model->email;
			$booking->confirmed = 1;

			// Create the booking for the partner
			$partner_booking = null;
			if($model->has_partner == 'yes'){
				$partner_booking = new Booking();
				$partner_booking->event_id = $event->id;
				$partner_booking->firstname = $model->partner_firstname;
				$partner_booking->lastname = $model->partner_lastname;
				$partner_booking->email = $model->partner_email;
				$partner_booking->confirmed = 1;
			}
			if($booking->save()){
				if(isset($partner_booking))
					$partner_booking->save();
				// Adding the selected activities
				foreach($model->participations as $unconfirmed_participation){

					$activity = $unconfirmed_participation->activity;
					// Create the participation for the main person
					$participation = new Participation();
					$participation->activity_id = $activity->id;
					$participation->booking_id = $booking->id;
					$participation->quantity = $unconfirmed_participation->quantity;
					$participation->role = $model->role;
					$participation->has_partner = ($model->has_partner == 'yes');
					$participation->save();

					if($activity->couple_activity == 1 && $model->has_partner == 'yes'){

						$partner = new Partner();
						$partner->firstname = $model->partner_firstname;
						$partner->lastname = $model->partner_lastname;
						$partner->participation_id = $participation->id;
						$partner->role = Role::invertRole($model->role);
						$partner->save();

						// Create the participation in the partner booking
						$partner_participation = new Participation();
						$partner_participation->activity_id = $activity->id;
						$partner_participation->booking_id = $partner_booking->id;
						$partner_participation->quantity = $unconfirmed_participation->quantity;
						$partner_participation->role = Role::invertRole($model->role);
						$partner_participation->has_partner = ($model->has_partner == 'yes');
						$partner_participation->save();

						// Create also a partner who is the main booking person
						$partner = new Partner();
						$partner->firstname = $model->firstname;
						$partner->lastname = $model->lastname;
						$partner->participation_id = $partner_participation->id;
						$partner->role = $model->role;
						$partner->save();
					}

				}
				if(isset($partner_booking)){
					$booking->partner_booking_id = $partner_booking->id;
					$partner_booking->partner_booking_id = $booking->id;
				}
				// Computing final price
				$booking->saveFinalPrice();
				if(isset($partner_booking))
					$partner_booking->saveFinalPrice();
				
				// Send a confirmation email
				$booking->sendEmailSummary();
				if(isset($partner_booking))
					$partner_booking->sendEmailSummary();

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