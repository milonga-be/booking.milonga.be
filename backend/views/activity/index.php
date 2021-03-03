<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('booking', 'Activities');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['activity/index', 'event_uuid' => $event->uuid]
    ]
];
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a class="btn btn-md btn-default" href="<?= Url::to(['/activity/create', 'event_uuid' => $event->uuid])?>"><?= Yii::t('booking', 'New')?></a>
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
    		'attribute' => 'searchSummary',
            'label' => Yii::t('booking', 'Title'),
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->summary, ['/activity/view', 'uuid' => $data->uuid]);
    		},
    	],
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d M, H:i'],
            'contentOptions' => ['class' => 'hide-xs text-muted'],
            'headerOptions' => ['class' => 'hide-xs']
        ],
        [
            'attribute' => 'activityGroup_title',
            'label' => Yii::t('booking', 'Type'),
            'value' => 'activityGroup.title'
        ],
        [
            'attribute' => '',
            'format' => 'raw',
            'value' => function ($data) {                      
                return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this item ?').'\')" class="text-danger" href="'.Url::to(['activity/delete', 'uuid' => $data->uuid]).'">x</a>';
            },
        ]
    ]
 ])
?>