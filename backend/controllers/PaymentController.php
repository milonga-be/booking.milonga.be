<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Booking;
use common\models\Payment;

/**
 * Site controller
 */
class PaymentController extends Controller
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
     * Create a new payment
     *
     * @return string
     */
    public function actionCreate($booking_uuid)
    {
        $model = new Payment();
        $booking = Booking::findOne(['uuid' => $booking_uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->booking_id = $booking->id;
            if($model->save()){
                $booking->total_paid = $booking->computeTotalPaid();
                $booking->save();
                if($booking->isPaymentComplete())
                    $booking->sendEmailPaymentComplete();
                // Redirect to the list page
                $this->redirect(['/booking/view', 'uuid' => $booking->uuid]);
                return;
            }
        }

        return $this->render('create', ['model' => $model, 'booking' => $booking]);
    }

    /**
     * Delete a model
     *
     * @return string
     */
    public function actionDelete($uuid)
    {
        $model = Payment::findOne(['uuid' => $uuid]);
        $booking = $model->booking;
        $model->delete();
        // Recompute the total paid
        $booking->total_paid = $booking->computeTotalPaid();
        $booking->save();

        $this->redirect(['booking/view', 'uuid' => $booking->uuid]);
    }
}