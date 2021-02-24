<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Participation;
use common\models\Booking;
use common\models\Partner;
use common\models\Activity;

/**
 * Site controller
 */
class PartnerController extends Controller
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
        $model = Partner::findOne(['uuid' => $uuid]);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Create a new model
     *
     * @return string
     */
    public function actionCreate($booking_uuid, $activity_uuid)
    {
        $participation = new Participation();
        $model = new Partner();
        $booking = Booking::findOne(['uuid' => $booking_uuid]);
        $activity = Activity::findOne(['uuid' => $activity_uuid]);

        if ($booking && $activity && $model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->save();
            $participation->booking_id = $booking->id;
            $participation->activity_id = $activity->id;
            $participation->partner_id = $model->id;
            if($participation->validate()){
                $participation->save();
                return $this->redirect(['/booking/view', 'uuid' => $booking->uuid]);
            }
        }
        
        return $this->render('create', ['model' => $model, 'activity' => $activity, 'booking' => $booking]);
        
    }

    /**
     * Update a model
     *
     * @return string
     */
    public function actionUpdate($uuid)
    {
        $model = Partner::findOne(['uuid' => $uuid]);
        $booking = $model->participation->booking;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/booking/view', 'uuid' => $booking->uuid]);
                return;
            }
        }

        return $this->render('update', ['model' => $model, 'booking' => $booking]);
    }
}
