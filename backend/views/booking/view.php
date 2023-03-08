<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->title = $model->name;
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
                    $confirm = 'onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this reservation {ref1} and the one of the partner {ref2} ?', ['ref1' => $model->getReference(), 'ref2' => $model->partnerBooking->getReference()]).'\')"';
                }else{
                    $confirm = 'onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this reservation ?').'\')"';
                }
                ?>
                <li><a class="dropdown-item" href="<?= Url::to(['booking/send-email-summary', 'uuid' => $model->uuid ]) ?>">Send Invoice</a></li>
                <li><a <?= $confirm?> class="dropdown-item" href="<?= Url::to(['booking/cancel', 'uuid' => $model->uuid, 'email' => 1 ]) ?>">Cancel &amp; Email</a></li>
                <li><a <?= $confirm?> class="dropdown-item" href="<?= Url::to(['booking/cancel', 'uuid' => $model->uuid, 'email' => 0 ]) ?>">Cancel Silently</a></li>
            </ul>
        </div>
    </div>
</div>
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
        [
            'label' => Yii::t('booking', 'Partner Reservation'),
            'format' => 'raw',
            'value' => function($data){
                if(isset($data->partnerBooking))
                    return Html::a($data->partnerBooking->name.' ('.$data->partnerBooking->getReference().')', ['booking/view', 'uuid' => $data->partnerBooking->uuid]);
                return null;
            },
        ],
        'source'
    ],
])?>
<p class="text-right">
    <a href="<?= Url::to(['booking/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
</p>
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
			<ul class="dropdown-menu">
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
    		'attribute' => 'activity.title',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->activity->title, ['/activity/view', 'uuid' => $data->activity->uuid]);
    		},
    	],
        [
            'attribute' => 'quantity'
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