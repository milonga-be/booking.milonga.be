<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('booking', 'Your reservations');
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a class="btn btn-md btn-default" href="<?= Url::to(['/booking/create', 'event_uuid' => $event->uuid])?>"><?= Yii::t('booking', 'Add')?></a>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $searchModel,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
        'firstname',
        'lastname',
    	[
    		'attribute' => 'email',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->email, ['/booking/view', 'uuid' => $data->uuid]);
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