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
class ReductionIncompatibilityController extends Controller
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
                        'actions' => ['create', 'delete'],
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
     * Create a new model
     *
     * @return string
     */
    public function actionCreate($reduction_uuid, $incompatible_reduction_uuid)
    {
        $reduction = Reduction::findOne(['uuid' => $reduction_uuid]);
        $incompatibleReduction = Reduction::findOne(['uuid' => $incompatible_reduction_uuid]);
        $reduction->link('incompatibleReductions', $incompatibleReduction);
        $this->redirect(['/reduction/view', 'uuid' => $reduction->uuid]);
    }

    /**
     * Delete a model
     *
     * @return string
     */
    public function actionDelete($reduction_uuid, $incompatible_reduction_uuid)
    {
        $reduction = Reduction::findOne(['uuid' => $reduction_uuid]);
        $incompatibleReduction = Reduction::findOne(['uuid' => $incompatible_reduction_uuid]);
        $reduction->unlink('incompatibleReductions', $incompatibleReduction, true);
        $this->redirect(['/reduction/view', 'uuid' => $reduction->uuid]);
    }
}
