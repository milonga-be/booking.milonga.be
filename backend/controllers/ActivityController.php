<?php
namespace backend\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\Event;
use common\models\Activity;
use common\models\Booking;
use common\models\ActivityGroup;
use backend\models\ActivitySearch;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Site controller
 */
class ActivityController extends Controller
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
                        'actions' => ['index', 'view', 'create', 'update', 'delete', 'export-participants'],
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
        $searchModel = new ActivitySearch();
        $searchModel->event_id = $event->id;
        $provider = $searchModel->search(Yii::$app->request->queryParams);
        $provider->pagination->pageSize = 50;

        return $this->render('index', ['searchModel' => $searchModel, 'provider' => $provider, 'event' => $event]);
    }

    /**
     * Show the details of a model
     * @param  string $uuid Unique Id of the model
     * @return string
     */
    public function actionView($uuid){
        $model = Activity::findOne(['uuid' => $uuid]);

        return $this->render('view', ['model' => $model]);
    }

    /**
     * Create a new model
     *
     * @return string
     */
    public function actionCreate($event_uuid, $activity_group_uuid)
    {
        $model = new Activity();
        $event = Event::findOne(['uuid' => $event_uuid]);
        $activityGroup = ActivityGroup::findOne(['uuid' => $activity_group_uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->event_id = $event->id;
            $model->activity_group_id = $activityGroup->id;
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/activity/index', 'event_uuid' => $event->uuid]);
                return;
            }
        }

        return $this->render('create', ['model' => $model, 'event' => $event, 'type' => $activityGroup->title]);
    }

    /**
     * Update a model
     *
     * @return string
     */
    public function actionUpdate($uuid)
    {
        $model = Activity::findOne(['uuid' => $uuid]);
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if($model->save()){
                // Redirect to the list page
                $this->redirect(['/activity/view', 'event_uuid' => $model->event->uuid, 'uuid' => $model->uuid]);
                return;
            }
        }

        return $this->render('update', ['model' => $model, 'event' => $model->event, 'type' => $model->activityGroup->title]);
    }

    /**
     * Delete a model
     *
     * @return string
     */
    public function actionDelete($uuid)
    {
        $model = Activity::findOne(['uuid' => $uuid]);
        $event = $model->event;
        $model->delete();

        $this->redirect(['activity/index', 'event_uuid' => $event->uuid]);
    }



    /**
     * Export a file with the activities and their participants
     *
     * @return string
     */
    public function actionExportParticipants($event_uuid){
        header('Content-type: application/vnd.openXMLformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="export.xlsx"');

        $objPHPExcel = new Spreadsheet();
        $sheet = $objPHPExcel->getActiveSheet();

        $headings = array(
            ['title' => Yii::t('booking', 'Participant'), 'width' => 30], 
            ['title' => Yii::t('booking', 'Partner'), 'width' => 30], 
            ['title' => Yii::t('booking', 'Total'), 'width' => 20], 
            ['title' => Yii::t('booking', 'Remaining'), 'width' => 20], 
        );

        $event = Event::findOne(['uuid' => $event_uuid]);
        foreach($event->activities as $activity){
            if($activity->activityGroup->title == 'Workshop'){
                $lineNr = 1;
                // Adding a summary of the activity
                $cellName = 'A'.$lineNr;
                if(isset($activity->datetime)){
                    $sheet->setCellValue($cellName, (new \Datetime($activity->datetime))->format('l F j - G:i'));
                }
                $lineNr++;
                $cellName = 'A'.$lineNr;
                $sheet->setCellValue($cellName, $activity->title);
                
                $cellName = 'B'.$lineNr;
                $sheet->setCellValue($cellName, (isset($activity->teacher)?$activity->teacher->name:''));
                $cellName = 'C'.$lineNr;
                $sheet->setCellValue($cellName, $activity->countParticipants().' participants');

                $sheet_title = '';
                // $sheet_title = substr($activity->activityGroup->title, 0, 2);
                // $sheet_title.=' ';
                // if(isset($activity->teacher))
                //     $sheet_title.=' '.$activity->teacher->name;
                // else
                $sheet_title.=' '.$activity->title;
                if(isset($activity->datetime)){
                    $sheet_title.=' '.(new \Datetime($activity->datetime))->format('D G.i');
                }
                $invalidCharacters = $sheet->getInvalidCharacters();
                $title = str_replace($invalidCharacters, '', $sheet_title);
                if(strlen($title) > 31){
                    $title = substr($title, 0, 31);
                }
                $sheet->setTitle($title);

                $cellNr = 0;
                $lineNr = 4;
                $rowNumber = 1;
                foreach ($headings as $heading) {
                    $header = $heading['title'];
                    if($header == 'Partner' && !$activity->couple_activity)
                        $header = '';
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
                $lineNr++;
                foreach($activity->confirmedParticipations as $participation){
                    // Participant
                    $cellName = 'A'.$lineNr;
                    $sheet->setCellValue($cellName, $participation->booking->name);
                
                    // Partner
                    $cellName = 'B'.$lineNr;
                    if(isset($participation->partner))
                        $sheet->setCellValue($cellName, $participation->partner->name);
                    else if($participation->quantity > 1)
                        $sheet->setCellValue($cellName, 'x '.$participation->quantity);

                    // Total
                    $total_price = 0;
                    $total_paid = 0;
                    $amountDue = 0;
                    $refs = [];
                    $bookings = Booking::find()->where(['email' => $participation->booking->email])->andWhere(['confirmed' => 1])->andWhere(['event_id' => $event->id])->all();
                    foreach($bookings as $booking){
                        $refs[] = $booking->reference;
                        $total_price+=$booking->total_price;
                        $total_paid+=$booking->total_paid;
                        $amountDue+=$booking->amountDue;
                    }
                    $cellName = 'C'.$lineNr;
                    $sheet->setCellValue($cellName, $total_price);

                    // Remaining
                    $cellName = 'D'.$lineNr;
                    $sheet->setCellValue($cellName, $amountDue);
                    $lineNr++;
                }
                $lineNr++;

                $sheet = $objPHPExcel->createSheet();
            }
        }

        $objWriter = new Xlsx($objPHPExcel);
        $tmpfile = @tempnam(ini_get('upload_tmp_dir'), "export");
        $objWriter->save( $tmpfile );

        fpassthru( fopen($tmpfile, 'rb') );
        exit();
    }
}
