<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use common\models\Event;
use common\models\Participant;
use common\models\Partner;
use common\models\Activity;
use common\models\Reduction;
use common\models\Booking;
use common\models\Participation;

/**
 * Site controller
 */
class EventController extends Controller
{

	/**
	 * The subscription form for the event
	 * @param  integer $id The event id 
	 * @return mixed
	 */
	public function actionRegistration($id){
		$event = $this->findModel($id);
		$participant = new Participant();
		$partner = new Partner();

		if(\Yii::$app->request->isPost){
			$participant->load(\Yii::$app->request->post());
			$partner->load(\Yii::$app->request->post());
			$post = \Yii::$app->request->post();
			if( $participant->validate() && $partner->validate() && isset($post['activity']) ){
				
				// Checking that the participant doesn't exist yet, if it does, reuse it
				$existing_participant = Participant::findOne(['email' => $participant->email]);
				if($existing_participant){
					$existing_participant->load(\Yii::$app->request->post());
					$participant = $existing_participant;
				}

				// Saving the participants
				$participant->save();
				$partner->save();

				// Saving the participations in the booking
				$booking = new Booking();
				$booking->firstname = $participant->firstname;
				$booking->lastname = $participant->lastname;
				$booking->email = $participant->email;
				$booking->phone = $participant->phone;
				$booking->total_price = $this->computeTotalPrice($event->id, $post['activity']);
				$booking->save();

				foreach ($post['activity'] as $activity_id) {
					$activity = Activity::findOne($activity_id);
					$participation = new Participation();
					$participation->activity_id = $activity->id;
					if($activity->couple_activity){
						$participation->participant1_id = $participant->id;
						$participation->participant2_id = $partner->id;
					}else{
						$participation->participant1_id = $participant->id;
					}
					$participation->save();
					$booking->link('participations', $participation);
				}
				$html = $this->render('registration-complete',['event' => $event, 'booking' => $booking]);

				Yii::$app->mailer->compose()
				    ->setFrom('booking@carlosyrosa.be')
				    ->setTo($booking->email)
				    ->setCc(Yii::$app->params['adminEmail'])
				    ->setSubject($event->title)
				    ->setHtmlBody($html)
				    ->send();

				return $html;
			}
		}

		return $this->render('registration',['event' => $event, 'participant' => $participant, 'partner' => $partner]);
	}

	/**
	 * Find an event
	 * @param  integer $id The event id 
	 * @return Event
	 */
	private function findModel($id){
		return Event::findOne($id);
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