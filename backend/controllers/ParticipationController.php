<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Participation;
use common\models\Participant;

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
        // $model = new ParticipationForm();
        // $activity = Activity::findOne(['uuid' => $activity_uuid]);
        // if ($model->load(Yii::$app->request->post()) && $model->validate()) {
        //     $participation = new Participation();
        //     $participation->activity_id = $activity->id;
        //     if($participation->save()){
        //         // Redirect to the list page
        //         $this->redirect(['/activity/view', 'uuid' => $activity->uuid]);
        //         return;
        //     }
        // }

        // return $this->render('create', ['model' => $model, 'activity' => $activity]);
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
        $activity = $model->activity;
        $model->delete();

        $this->redirect(['activity/view', 'uuid' => $activity->uuid]);
    }
}
