<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Event;
use common\models\Booking;
use backend\models\EventSearch;
use backend\models\BookingSearch;
use backend\models\ActivitySearch;

/**
 * Site controller
 */
class EventController extends Controller
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
     * Displays event list.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new EventSearch();
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'provider' => $provider]);
    }

    /**
     * Show the details of a model
     * @param  string $uuid Unique Id of the model
     * @return string
     */
    public function actionView($uuid){
        $model = Event::findOne(['uuid' => $uuid]);

        // Reservations
        $searchModel = new BookingSearch();
        $searchModel->event_id = $model->id;
        $bookingProvider = $searchModel->search(Yii::$app->request->queryParams);

        // Activities
        $searchModel = new ActivitySearch();
        $searchModel->event_id = $model->id;
        $activityProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('view', ['model' => $model, 'bookingProvider' => $bookingProvider, 'activityProvider' => $activityProvider]);
    }

    /**
     * Create a new model
     *
     * @return string
     */
    public function actionCreate()
    {
        $model = new Event();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/event/index']);
                return;
            }
        }

        return $this->render('create', ['model' => $model]);
    }

    /**
     * Update a model
     *
     * @return string
     */
    public function actionUpdate($uuid)
    {
        $model = Event::findOne(['uuid' => $uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/event/view', 'uuid' => $model->uuid]);
                return;
            }
        }

        return $this->render('update', ['model' => $model]);
    }

    /**
     * Delete a model
     *
     * @return string
     */
    public function actionDelete($uuid)
    {
        $model = Event::findOne(['uuid' => $uuid]);
        $model->delete();

        $this->redirect(['event/index']);
    }
}
