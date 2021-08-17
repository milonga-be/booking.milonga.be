<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('booking', 'Reductions');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['reduction/index', 'event_uuid' => $event->uuid]
    ]
];
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a class="btn btn-md btn-default" href="<?= Url::to(['/reduction/create', 'event_uuid' => $event->uuid])?>"><?= Yii::t('booking', 'New')?></a>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $searchModel,
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
        [
            'attribute' => '',
            'format' => 'raw',
            'value' => function ($data) {                      
                return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this item ?').'\')" class="text-danger" href="'.Url::to(['reduction/delete', 'uuid' => $data->uuid]).'">x</a>';
            },
        ]
    ]
 ])
?>