<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Event;
use common\models\Reduction;
use common\models\ReductionRule;
use backend\models\ReductionSearch;

/**
 * Site controller
 */
class ReductionRuleController extends Controller
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
                        'actions' => ['view', 'create', 'update', 'delete'],
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
        $model = ReductionRule::findOne(['uuid' => $uuid]);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Create a new model
     *
     * @return string
     */
    public function actionCreate($reduction_uuid)
    {
        $model = new ReductionRule();
        $reduction = Reduction::findOne(['uuid' => $reduction_uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->reduction_id = $reduction->id;
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/reduction/view', 'uuid' => $reduction->uuid]);
                return;
            }
        }

        return $this->render('create', ['model' => $model, 'reduction' => $reduction]);
    }

    /**
     * Update a model
     *
     * @return string
     */
    public function actionUpdate($uuid)
    {
        $model = ReductionRule::findOne(['uuid' => $uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the reduction page
                $this->redirect(['/reduction/view', 'uuid' => $model->reduction->uuid]);
                return;
            }
        }

        return $this->render('update', ['model' => $model, 'reduction' => $model->reduction]);
    }

    /**
     * Delete a model
     *
     * @return string
     */
    public function actionDelete($uuid)
    {
        $model = ReductionRule::findOne(['uuid' => $uuid]);
        $reduction = $model->reduction;
        $model->delete();

        $this->redirect(['reduction/index', 'uuid' => $reduction->uuid]);
    }
}
