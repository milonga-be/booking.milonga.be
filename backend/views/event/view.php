<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->title;
$this->params['breadcrumbs'] = [
	[
		'label' => $model->title,
		'url' => ['event/view', 'uuid' => $model->uuid]
	]
];
?>
<!--div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a href="<?= Url::to(['event/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
	</div>
</div-->
<h1><?= $this->title ?></h1>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title', 
        'email', 
        'website', 
        [
        	'attribute' => 'bookingUrl', 
        	'format' => 'raw',
        	'value' => function($data){
        		return Html::a('Booking Url', $data->bookingUrl, ['target' => '_blank']);
        	}
        ],
        'start_date:date', // creation date formatted as datetime
        'end_date:date', // creation date formatted as datetime
    ],
])?>
<p class="text-right">
	<a href="<?= Url::to(['event/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
</p>
<hr>
<div class="row">
	<div class="col-md-6">
		<h2 class="text-center"><a class="btn btn-default btn-md" href="<?= Url::to(['activity/index', 'event_uuid' => $model->uuid])?>"><?= Yii::t('booking', 'Activities') ?></a></h2>
		<?= GridView::widget([
		    'dataProvider' => $activityProvider,
		    'showHeader'=> false,
		    'layout' => '{items}{pager}',
		    'tableOptions' => ['class' => 'table table-hover table-striped'],
		    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
		    'columns' => [
		    	[
		    		'attribute' => 'title',
		    		'format' => 'raw',
		    		'value' => function($data){
		    			return Html::a((strlen($data->title) > 50)?(substr($data->title,0, 50).'...'):$data->title, ['/activity/view', 'uuid' => $data->uuid]);
		    		},
		    	],
		        // [
		        //     'attribute' => 'created_at',
		        //     'format' => ['date', 'php:d M, H:i'],
		        //     'contentOptions' => ['class' => 'hide-xs text-muted'],
		        //     'headerOptions' => ['class' => 'hide-xs']
		        // ],
		    ]
		 ])
		?>
	</div>
	<div class="col-md-6">
		<h2 class="text-center"><a class="btn btn-default btn-md" href="<?= Url::to(['booking/index', 'event_uuid' => $model->uuid])?>"><?= Yii::t('booking', 'Reservations') ?></a></h2>
		<?= GridView::widget([
		    'dataProvider' => $bookingProvider,
		    'showHeader'=> false,
		    'layout' => '{items}{pager}',
		    'tableOptions' => ['class' => 'table table-hover  table-striped'],
		    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
		    'columns' => [
		    	[
		    		'attribute' => 'lastname',
		    		'format' => 'raw',
		    		'value' => function($data){
		    			return Html::a($data->lastname.' '.$data->firstname, ['/booking/view', 'uuid' => $data->uuid]);
		    		},
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
	</div>
</div>
<div class="row">
	<div class="col-md-6">
		<h2 class="text-center"><a class="btn btn-default btn-md" href="<?= Url::to(['teacher/index', 'event_uuid' => $model->uuid])?>"><?= Yii::t('booking', 'Teachers') ?></a></h2>
		<?= GridView::widget([
		    'dataProvider' => $teacherProvider,
		    'showHeader'=> false,
		    'layout' => '{items}{pager}',
		    'tableOptions' => ['class' => 'table table-hover  table-striped'],
		    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
		    'columns' => [
		    	[
		    		'attribute' => 'name',
		    		'format' => 'raw',
		    		'value' => function($data){
		    			return Html::a($data->name, ['/teacher/view', 'uuid' => $data->uuid]);
		    		},
		    	],
		    ]
		 ])
		?>
	</div>
	<div class="col-md-6">
		<h2 class="text-center"><a class="btn btn-default btn-md" href="<?= Url::to(['reduction/index', 'event_uuid' => $model->uuid])?>"><?= Yii::t('booking', 'Reductions') ?></a></h2>
		<?= GridView::widget([
		    'dataProvider' => $reductionProvider,
		    'showHeader'=> false,
		    'layout' => '{items}{pager}',
		    'tableOptions' => ['class' => 'table table-hover  table-striped'],
		    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
		    'columns' => [
		    	[
		    		'attribute' => 'name',
		    		'format' => 'raw',
		    		'value' => function($data){
		    			return Html::a($data->name, ['/reduction/view', 'uuid' => $data->uuid]);
		    		},
		    	],
		    ]
		 ])
		?>
	</div>
</div>