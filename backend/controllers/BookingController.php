<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Booking;
use common\models\Event;
use backend\models\BookingSearch;
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'export-payments'],
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

        $amount_datas = [];
        $date = new \Datetime($event->start_date);
        $date->modify('-12 month');

        for($i = 0;$i<12;$i++){
            $amount_datas[$date->format('M')] = (Booking::find()
                ->where(['LIKE', 'created_at', $date->format('Y-m-')])
                ->andWhere(['=', 'event_id', $event->id])
                ->select('SUM(total_price) AS total')->asArray()->one())['total'];
            $quantity_datas[$date->format('M')] = Booking::find()
                ->where(['LIKE', 'created_at', $date->format('Y-m-')])
                ->andWhere(['=', 'event_id', $event->id])
                ->count();
            $date->modify('+1 month');
        }

        return $this->render('index', ['searchModel' => $searchModel, 'provider' => $provider, 'event' => $event, 'amount_datas' => $amount_datas, 'quantity_datas' => $quantity_datas]);
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
        $model->delete();

        $this->redirect(['booking/index', 'event_uuid' => $event->uuid]);
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
            ['title' => Yii::t('booking', 'Lastname'), 'width' => 30], 
            ['title' => Yii::t('booking', 'Firstname'), 'width' => 30], 
            ['title' => Yii::t('booking', 'Amount to pay'), 'width' => 20], 
            ['title' => Yii::t('booking', 'Total Paid'), 'width' => 20]
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
        foreach($event->bookings as $booking){
            // Lastname
            $cellName = 'A'.$lineNr;
            $sheet->setCellValue($cellName, $booking->lastname);
        
            // Firstname
            $cellName = 'B'.$lineNr;
            $sheet->setCellValue($cellName, $booking->firstname);

            // Price
            $cellName = 'C'.$lineNr;
            $sheet->setCellValue($cellName, $booking->total_price);

            // Price
            $cellName = 'D'.$lineNr;
            $sheet->setCellValue($cellName, $booking->paid);

            $lineNr++;
        }

        $objWriter = new Xlsx($objPHPExcel);
        $tmpfile = tempnam(sys_get_temp_dir(), "export");
        $objWriter->save( $tmpfile );

        fpassthru( fopen($tmpfile, 'rb') );
        exit();
    }
}
