<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Booking;
use common\models\Event;
use common\models\Payment;
use backend\models\BookingSearch;
use backend\models\CancelledBookingSearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'export-payments', 'stats', 'send-email-summary', 'cancel', 'cancelled-list'],
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

        // Computing total Price
        // Computing paid / not paid
        $total_query_row = Booking::find()
                ->where(['=', 'event_id', $event->id])
                ->andWhere(['confirmed' => 1])
                ->select('SUM(total_price) AS total')->asArray()->one();
        $total = $total_query_row['total'];

        return $this->render('index', ['searchModel' => $searchModel, 'provider' => $provider, 'event' => $event, 'total' => $total]);
    }

    /**
     * Displays the cancelled booking.
     *
     * @return string
     */
    public function actionCancelledList($event_uuid)
    {
        $event = Event::findOne(['uuid' => $event_uuid]);
        $searchModel = new CancelledBookingSearch();
        $searchModel->event_id = $event->id;
        $provider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('cancelled-list', ['searchModel' => $searchModel, 'provider' => $provider, 'event' => $event]);
    }

    /**
     * Display the stats for this event
     * @param  string $event_uuid The event identifier
     * @return mixed
     */
    public function actionStats($event_uuid){
        $event = Event::findOne(['uuid' => $event_uuid]);

        $amount_datas = [];
        $date = new \Datetime($event->start_date);
        $date->modify('-12 month');

        // Sum booking Prices and number of bookings
        for($i = 0;$i<12;$i++){
            $total_query_row = Booking::find()
                ->where(['LIKE', 'created_at', $date->format('Y-m-')])
                ->andWhere(['=', 'event_id', $event->id])
                ->andWhere(['confirmed' => 1])
                ->select('SUM(total_price) AS total')->asArray()->one();
            $amount_datas[$date->format('M')] = $total_query_row['total'];
            $date->modify('+1 month');
        }
        $date = new \Datetime($event->start_date);
        $start = new \Datetime($event->start_date);
        $date->modify('-12 month');
        do{
            $quantity_datas[$date->format('Ymd')] = Booking::find()
                ->where(['LIKE', 'created_at', $date->format('Y-m-d')])
                ->andWhere(['=', 'event_id', $event->id])
                ->andWhere(['confirmed' => 1])
                ->count();
            $date->modify('+1 day');
        }while($date < $start);

        // Computing paid / not paid
        $total_query_row = Booking::find()
                ->where(['=', 'event_id', $event->id])
                ->andWhere(['confirmed' => 1])
                ->select('SUM(total_price) AS total')->asArray()->one();
        $total = $total_query_row['total'];
        $paid_query_row = Payment::find()
                ->joinWith('booking', false)
                ->where(['=', 'booking.event_id', $event->id])
                ->andWhere(['booking.confirmed' => 1])
                ->select('SUM(payment.amount) AS total')->asArray()->one();
        $paid = (int)$paid_query_row['total'];
        $not_paid = $total - $paid;

        return $this->render('stats', ['event' => $event, 'amount_datas' => $amount_datas, 'quantity_datas' => $quantity_datas, 'paid' => $paid, 'not_paid' => $not_paid]);
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
                $this->redirect(['/booking/view','uuid' => $model->uuid, 'event_uuid' => $event->uuid]);
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
                $this->redirect(['/booking/view', 'event_uuid' => $model->event->uuid, 'uuid' => $model->uuid]);
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
        $model->softDelete();

        $this->redirect(['booking/index', 'event_uuid' => $event->uuid]);
    }

    /**
     * Cancel a reservation
     *
     * @return string
     */
    public function actionCancel($uuid, $email)
    {
        $model = Booking::findOne(['uuid' => $uuid]);
        $event = $model->event;
        if($email){
            if(isset($model->partnerBooking)){
                $model->partnerBooking->sendEmailCancelled();
            }
            $model->sendEmailCancelled();
        }
        $model->softDelete();

        $this->redirect(['booking/index', 'event_uuid' => $event->uuid]);
    }

    /**
     * Send a summary
     *
     * @return string
     */
    public function actionSendEmailSummary($uuid)
    {
        $model = Booking::findOne(['uuid' => $uuid]);
        $model->sendEmailSummary();

        $this->redirect(['booking/view', 'uuid' => $model->uuid]);
    }

    /**
     * Export a file with the participant and their payments
     *
     * @return string
     */
    public function actionExportPayments($event_uuid){
        header('Content-type: application/vnd.openXMLformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export.xlsx"');

        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();
        $lineNr = 1;
        $rowNumber = 1;
        $headings = array(
            ['title' => Yii::t('booking', 'Lastname'), 'width' => 20], 
            ['title' => Yii::t('booking', 'Firstname'), 'width' => 20], 
            ['title' => Yii::t('booking', 'Total'), 'width' => 10], 
            ['title' => Yii::t('booking', 'Paid'), 'width' => 10],
            ['title' => Yii::t('booking', 'Remaining'), 'width' => 10],
            // ['title' => Yii::t('booking', 'Ref'), 'width' => 15], 
        );
        // First building the columns titles
        $cellNr = 0;
        foreach ($headings as $heading) {
            $header = $heading['title'];
            $letter = chr($cellNr + 65);
            $cellName = $letter.$lineNr;
            $sheet->setCellValue($cellName, $header);
            // Forcing the width of the columns
            if(isset($heading['width'])){
                $sheet->getColumnDimension($letter)->setWidth($heading['width']);
            }
            // In bold
            $sheet->getStyle( $cellName )->getFont()->setBold(true);
            $cellNr++;
        }
        $event = Event::findOne(['uuid' => $event_uuid]);
        $lineNr++;
        // $bookings = $event->getConfirmedBookings()->orderBy('lastname,firstname')->all();
        $emails = Booking::find()->where(['confirmed' => 1])->andWhere(['event_id' => $event->id])->andWhere('email != ""')->select('DISTINCT(email)')->orderBy('lastname,firstname')->asArray()->all();
        $lines = [];
        foreach($emails as $line){
            $email = $line['email'];
            $bookings = Booking::find()->where(['email' => $email])->andWhere(['confirmed' => 1])->andWhere(['event_id' => $event->id])->all();
            $total_price = 0;
            $total_paid = 0;

            foreach($bookings as $booking){
                $total_price+=$booking->total_price;
                $total_paid+=$booking->total_paid;
            }
            
            $lines[] = [
                'lastname' => $bookings[0]->lastname,
                'firstname' => $bookings[0]->firstname,
                'total_price' => $total_price,
                'total_paid' => $total_paid,
                'amountDue' => ($total_price - $total_paid > 0 )?($total_price - $total_paid):0,
            ];
            
        }
        $no_emails_bookings = Booking::find()->where(['confirmed' => 1])->andWhere(['event_id' => $event->id])->andWhere('email = ""')->orderBy('lastname,firstname')->all();
        foreach($no_emails_bookings as $booking){
            $lines[] = [
                'lastname' => $booking->lastname,
                'firstname' => $booking->firstname,
                'total_price' => $booking->total_price,
                'total_paid' => $booking->total_paid,
                'amountDue' => $booking->amountDue,
            ];
        }
        uasort($lines, function($a, $b){
            if(strtolower($a['lastname']) == strtolower($b['lastname'])){
                return (strtolower($a['firstname']) < strtolower($b['firstname']))? -1 : 1;
            }
            return (strtolower($a['lastname']) < strtolower($b['lastname'])) ? -1 : 1;
        });
        foreach($lines as $line){
            // Lastname
            $cellName = 'A'.$lineNr;
            $sheet->setCellValue($cellName, $line['lastname']);
        
            // Firstname
            $cellName = 'B'.$lineNr;
            $sheet->setCellValue($cellName, $line['firstname']);

            // Total
            $cellName = 'C'.$lineNr;
            $sheet->setCellValue($cellName, $line['total_price']);

            // Paid
            $cellName = 'D'.$lineNr;
            $sheet->setCellValue($cellName, $line['total_paid']);

            // Remaining
            $cellName = 'E'.$lineNr;
            $sheet->setCellValue($cellName, $line['amountDue']);

            // Refs
            // $cellName = 'F'.$lineNr;
            // $sheet->setCellValue($cellName, implode(',', $refs));
            $lineNr++;
        }
        

        $objWriter = new Xlsx($objPHPExcel);
        $tmpfile = tempnam(ini_get('upload_tmp_dir'), "export");
        $objWriter->save( $tmpfile );

        fpassthru( fopen($tmpfile, 'rb') );
        exit();
    }
}
