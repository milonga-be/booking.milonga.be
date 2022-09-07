<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use backend\models\ParticipationForm;
use common\models\Participation;
use common\models\Booking;
use common\models\Partner;
use common\models\Activity;

/**
 * Site controller
 */
class ParticipationController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Show the details of a model
     * @param  string $uuid Unique Id of the model
     * @return string
     */
    public function actionView($uuid){
        $model = Participation::findOne(['uuid' => $uuid]);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Create a new model
     *
     * @return string
     */
    public function actionCreate($booking_uuid, $activity_uuid)
    {
        $booking = Booking::findOne(['uuid' => $booking_uuid]);
        $activity = Activity::findOne(['uuid' => $activity_uuid]);
        if($activity->couple_activity){
            $model = new ParticipationForm();
            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $participation = new Participation();
                $participation->booking_id = $booking->id;
                $participation->activity_id = $activity->id;
                $participation->role = $model->role;
                $participation->has_partner = ($model->has_partner == 'yes');
                $participation->save();
                if($model->has_partner == 'yes'){
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
                $this->redirect(['/booking/view', 'uuid' => $booking->uuid]);
            }
            return $this->render('create', ['model' => $model, 'event' => $activity->event, 'booking' => $booking, 'activity' => $activity]);
        }
        if($booking && $activity){
            $participation = new Participation();
            $participation->booking_id = $booking->id;
            $participation->activity_id = $activity->id;
            if($participation->validate()){
                $participation->save();

            }
        }
        $this->redirect(['/booking/view', 'uuid' => $booking->uuid]);
    }

    /**
     * Update a model
     *
     * @return string
     */
    public function actionUpdate($uuid)
    {
        $model = Participation::findOne(['uuid' => $uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/activity/view', 'uuid' => $model->activity->uuid]);
                return;
            }
        }

        return $this->render('update', ['model' => $model, 'activity' => $model->activity]);
    }

    /**
     * Delete a model
     *
     * @return string
     */
    public function actionDelete($uuid)
    {
        $model = Participation::findOne(['uuid' => $uuid]);
        $booking = $model->booking;
        $model->delete();

        $this->redirect(['booking/view', 'uuid' => $booking->uuid]);
    }
}
