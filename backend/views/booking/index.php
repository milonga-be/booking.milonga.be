<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\assets\ChartJsAsset;

ChartJsAsset::register($this);

$this->title = Yii::t('booking', 'Reservations');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['booking/index', 'event_uuid' => $event->uuid]
    ]
];
?>
<div class="row">
	<div class="col-md-9">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-3 text-right">
        <a class="btn btn-md btn-default" href="<?= Url::to(['/booking/stats', 'event_uuid' => $event->uuid])?>"><?= Yii::t('booking', 'Statistics')?></a> 
        <a class="btn btn-md btn-default" href="<?= Url::to(['/booking/create', 'event_uuid' => $event->uuid])?>"><?= Yii::t('booking', 'New')?></a>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $searchModel,
    'layout' => '{items}'.'<a class="export pull-right btn btn-md btn-default" href="'.Url::to(['booking/export-payments', 'event_uuid' => $event->uuid]).'">'.Yii::t('booking', 'Export').'</a>'.'{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'options' => ['class' => 'mb-4'],
    'columns' => [
        [
            'attribute' => 'id',
            'value' => 'reference',
            'label' => Yii::t('booking', 'Ref.')
        ],
        'name',
    	[
    		'attribute' => 'email',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->email, ['/booking/view', 'uuid' => $data->uuid]);
    		},
    	],
        'total_price:currency',
        [
            'attribute' => 'created_at',
            'format' => ['date', 'php:d M, H:i'],
            'contentOptions' => ['class' => 'hide-xs text-muted'],
            'headerOptions' => ['class' => 'hide-xs']
        ],
        [
            'attribute' => '',
            'format' => 'raw',
            'value' => function ($data) {                      
                return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this item ?').'\')" class="text-danger" href="'.Url::to(['booking/delete', 'uuid' => $data->uuid]).'">x</a>';
            },
        ],
    ]
 ])
?>