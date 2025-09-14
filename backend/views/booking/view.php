<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelLow;
use Endroid\QrCode\Writer\PngWriter;

$this->title = $model->name.' ('.$model->getReference().')';
$this->params['breadcrumbs'] = [
    [
        'label' => $model->event->title,
        'url' => ['event/view', 'uuid' => $model->event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Reservations'),
        'url' => ['booking/index', 'event_uuid' => $model->event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['booking/view', 'uuid' => $model->uuid]
    ]
];

$qrCodeResult = Builder::create()
    ->writer(new PngWriter())
    ->data($model->uuid)
    ->encoding(new Encoding('UTF-8'))
    ->errorCorrectionLevel(new ErrorCorrectionLevelLow())
    ->size(150)
    ->margin(5)
    ->build();
$qrCodeDataUri = $qrCodeResult->getDataUri();

?>
<div class="row">
    <div class="col-md-10">
        <h1><?= $this->title ?></h1>
    </div>
    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= Yii::t('booking', 'Actions')?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php
                if(isset($model->partnerBooking)){
                    $confirm = 'onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this reservation {ref1} ? If necessary also delete the one of the partner {ref2} !', ['ref1' => $model->getReference(), 'ref2' => $model->partnerBooking->getReference()]).'\')"';
                }else{
                    $confirm = 'onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this reservation ?').'\')"';
                }
                ?>
                <?php if(!$model->confirmed){ ?>
                    <li><a class="dropdown-item" href="<?= Url::to(['booking/restore', 'uuid' => $model->uuid ]) ?>"><?= Yii::t('booking', 'Restore')?></a></li>
                <?php }else{ ?>
                <li><a class="dropdown-item" href="<?= Url::to(['booking/send-email-summary', 'uuid' => $model->uuid ]) ?>">Send Invoice</a></li>
                <li><a <?= $confirm?> class="dropdown-item" href="<?= Url::to(['booking/cancel', 'uuid' => $model->uuid, 'email' => 1 ]) ?>">Cancel &amp; Email</a></li>
                <li><a <?= $confirm?> class="dropdown-item" href="<?= Url::to(['booking/cancel', 'uuid' => $model->uuid, 'email' => 0 ]) ?>">Cancel Silently</a></li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-9">
        <?= DetailView::widget([
            'model' => $model,
            'attributes' => [
                'created_at:datetime',
                'reference', 
                'firstname', 
                'lastname', 
                'email',
                'total_price:currency',
                'total_paid:currency',
                /*[
                    'label' => Yii::t('booking', 'Partner Reservation'),
                    'format' => 'raw',
                    'value' => function($data){
                        if(isset($data->partnerBooking))
                            return Html::a($data->partnerBooking->name.' ('.$data->partnerBooking->getReference().')', ['booking/view', 'uuid' => $data->partnerBooking->uuid]);
                        return null;
                    },
                ],*/
                'source'
            ],
        ])?>
        <p class="text-right">
            <a href="<?= Url::to(['booking/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
        </p>
    </div>
    <div class="col-md-3 text-center" style="padding-top:25px;">
        <img src="<?= $qrCodeDataUri ?>" alt="QR Code">
        <h3><?= $model->getReference() ?></h3>
    </div>
</div>
<?php
$participationsProvider = new ArrayDataProvider([
    'allModels' => $model->participations,
    'pagination' => false,
]);
?>
<hr>
<div class="row">
	<div class="col-md-10">
		<h2><?= Yii::t('booking', 'Activities')?></h2>
	</div>
	<div class="col-md-2 text-right">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    			<?= Yii::t('booking', 'Add')?> <span class="caret"></span>
  			</button>
			<ul class="dropdown-menu dropdown-menu-right">
				<?php $activities = $model->getActivitiesList(); ?>
				<?php foreach ($activities as $uuid => $title) {
					echo '<li><a href="'.Url::to(['participation/create', 'booking_uuid' => $model->uuid, 'activity_uuid' => $uuid]).'">'.$title.'</a></li>';
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $participationsProvider,
    'showHeader'=> true,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'columns' => [
        [
            'label' => Yii::t('booking', 'Registered'),
            'format' => 'raw',
            'headerOptions' => ['class' => 'text-center'],
            'contentOptions' => ['class' => 'text-center'],
            'value' => function($data) {
                $icon = $data->registered ? 'check' : 'unchecked';
                $class = $data->registered ? 'text-success' : 'text-muted';
                $url = Url::to(['participation/toggle-registered', 'uuid' => $data->uuid]);
                return Html::a('<span class="glyphicon glyphicon-'.$icon.'"></span>', $url, ['class' => $class]);
            }
        ],
        [
            'attribute' => 'activity.teacher.name',
            'format' => 'raw',
            'value' => function($data){
                if(isset($data->activity->teacher))
                    return Html::a((strlen($data->activity->teacher->name) > 50)?(substr($data->activity->teacher->name,0, 50).'...'):$data->activity->teacher->name, ['/activity/view', 'uuid' => $data->activity->uuid]);
            },
        ],
    	[
    		'attribute' => 'activity.title',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a((strlen($data->activity->title) > 50)?(substr($data->activity->title,0, 50).'...'):$data->activity->title, ['/activity/view', 'uuid' => $data->activity->uuid]);
    		},
    	],
        [
            'attribute' => 'activity.datetime',
            'label' => Yii::t('booking', 'Date'),
            'format' => 'datetime'
        ],
        [
            'attribute' => 'quantity',
            'label' => 'Quantity',
            'value' => function ($data) {
                return (($data->quantity > 1)?$data->times_registered.'/'.$data->quantity.' ':$data->quantity);
            },
        ],
        [
            'attribute' => 'role',
            'format' => 'raw',
            'value' => function($data){
                if(isset($data->activity->couple_activity))
                    return $data->role;
                else
                    return '-';
            },
        ],
    	[
    		'attribute' => 'partner.name',
    		'format' => 'raw',
    		'value' => function($data){
    			if(isset($data->partner))
    				return Html::a($data->partner->name, ['/partner/update', 'uuid' => $data->partner->uuid]);
    			else
    				return '-';
    		},
    	],
    	[
		    'attribute' => 'Delete',
		    'format' => 'raw',
		    'value' => function ($data) {                      
		        return '<a class="text-danger" href="'.Url::to(['participation/delete', 'uuid' =>$data->uuid]).'">x</a>';
		    },
		],
    ]
 ])
?>
<?php
$paymentsProvider = new ArrayDataProvider([
    'allModels' => $model->payments,
    'pagination' => false,
]);
?>
<hr>
<div class="row">
    <div class="col-md-10">
        <h2><?= Yii::t('booking', 'Payments')?></h2>
    </div>
    <div class="col-md-2 text-right">
        <div class="btn-group">
            <a href="<?= Url::to(['payment/create', 'booking_uuid' => $model->uuid]) ?>" class="btn btn-default">
                <?= Yii::t('booking', 'New')?>
            </a>
        </div>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $paymentsProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'columns' => [
        [
            'attribute' => 'amount',
            'format' => 'raw',
            'value' => function($data){
                return Html::a(Yii::$app->formatter->asCurrency($data->amount), ['/payment/update', 'uuid' => $data->uuid]);
            },
        ],
        'type',
        [
            'attribute' => 'Delete',
            'format' => 'raw',
            'value' => function ($data) {                      
                return '<a class="text-danger" href="'.Url::to(['payment/delete', 'uuid' =>$data->uuid]).'">x</a>';
            },
        ],
    ]
 ])
?>
<div class="row">
    <div class="col-md-10">
        <h2><?= Yii::t('booking', 'Other Reservations')?></h2>
    </div>
</div>
<?php
$otherReservationsProvider = new ArrayDataProvider([
    'allModels' => $model->otherReservations,
    'pagination' => false,
]);
?>
<?= GridView::widget([
    'dataProvider' => $otherReservationsProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'columns' => [
        [
            'attribute' => 'id',
            'format' => 'raw',
            'value' => function($data){
                return Html::a($data->reference, ['/booking/view', 'uuid' => $data->uuid]);
            },
            'label' => Yii::t('booking', 'Ref.')
        ],
        [
            'attribute' => 'name_search',
            'format' => 'raw',
            'label' => Yii::t('booking', 'Name'),
            'value' => function($data){
                return Html::a($data->name, ['/booking/view', 'uuid' => $data->uuid]);
            },
        ],
        [
            'attribute' => 'total_price',
            'format' => 'raw',
            'value' => function($data){
                return Yii::$app->formatter->asCurrency($data->total_price).(($data->isPaymentComplete())?' <span class="glyphicon glyphicon-ok-circle text-success"></span>':'');
            }
        ],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d M, H:i'],
            'contentOptions' => ['class' => 'hide-xs text-muted'],
            'headerOptions' => ['class' => 'hide-xs']
        ],
    ]
 ])
?>