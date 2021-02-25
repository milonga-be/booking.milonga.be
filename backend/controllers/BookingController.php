<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Booking;
use common\models\Event;
use backend\models\BookingSearch;

/**
 * Site controller
 */
class BookingController extends Controller
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
     * Displays booking list.
     *
     * @return string
     */
    public function actionIndex($event_uuid)
    {
        $event = Event::findOne(['uuid' => $event_uuid]);
        $searchModel = new BookingSearch();
        $searchModel->event_id = $event->id;
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', ['searchModel' => $searchModel, 'provider' => $provider, 'event' => $event]);
    }

    /**
     * Show the details of a model
     * @param  string $uuid Unique Id of the model
     * @return string
     */
    public function actionView($uuid){
        $model = Booking::findOne(['uuid' => $uuid]);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Create a new model
     *
     * @return string
     */
    public function actionCreate($event_uuid)
    {
        $model = new Booking();
        $event = Event::findOne(['uuid' => $event_uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->event_id = $event->id;
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/booking/index', 'event_uuid' => $event->uuid]);
                return;
            }
        }

        return $this->render('create', ['model' => $model, 'event' => $event]);
    }

    /**
     * Update a model
     *
     * @return string
     */
    public function actionUpdate($uuid)
    {
        $model = Booking::findOne(['uuid' => $uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/booking/index']);
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
        $model = Booking::findOne(['uuid' => $uuid]);
        $event = $model->event;
        $model->delete();

        $this->redirect(['booking/index', 'event_uuid' => $event->uuid]);
    }
}
