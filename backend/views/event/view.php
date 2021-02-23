<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = $model->title;
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a href="<?= Url::to(['event/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
	</div>
</div>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title', 
        'start_date:datetime', // creation date formatted as datetime
        'end_date:datetime', // creation date formatted as datetime
    ],
])?>
<div class="row">
	<div class="col-md-6">
		<h2><?= Yii::t('booking', 'Last Reservations')?></h2>
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
		<p class="text-right">
			<a class="btn btn-sm btn-default" href="<?= Url::to(['booking/index', 'event_uuid' => $model->uuid])?>">
				<?= Yii::t('booking', 'See the complete list')?>
			</a>
		</p>
	</div>
	<div class="col-md-6">
		<h2><?= Yii::t('booking', 'Payment balance')?></h2>
	</div>
</div>
<div class="row">
	<div class="col-md-10">
		<h2><?= Yii::t('booking', 'Activities')?></h2>
	</div>
	<div class="col-md-2 text-right">
		<a href="<?= Url::to(['activity/create', 'event_uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Add') ?></a>
	</div>
</div>
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
    			return Html::a($data->title, ['/activity/view', 'uuid' => $data->uuid]);
    		},
    	],
        [
            'attribute' => 'datetime',
            'format' => ['date', 'php:d M, H:i'],
            'contentOptions' => ['class' => 'hide-xs text-muted'],
            'headerOptions' => ['class' => 'hide-xs']
        ],
    ]
 ])
?>
