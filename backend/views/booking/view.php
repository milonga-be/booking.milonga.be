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
<h1><?= $this->title ?></h1>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        // 'created_at:datetime', 
        'firstname', 
        'lastname', 
        'email',
        'total_price:currency',
        'paid:currency',
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
    			<?= Yii::t('booking', 'New')?> <span class="caret"></span>
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
    'showHeader'=> false,
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
                return Html::a(Yii::$app->formatter->asCurrency($data->amount), ['/payment/view', 'uuid' => $data->uuid]);
            },
        ],
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