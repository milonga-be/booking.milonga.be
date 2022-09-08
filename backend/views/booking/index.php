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
$color_amount = '#61ab3b';
$color_quantity = '#2c4399';
?>
<div class="row">
	<div class="col-md-9">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-3 text-right">
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
<h3><?= Yii::t('booking', 'Total')?></h3>
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <canvas id="bars-amounts" width="100" height="300"></canvas>
    </div>
</div>
<h3><?= Yii::t('booking', 'Reservations')?></h3>
<div class="row">
    <div class="col-md-12 text-center">
        <canvas id="bars-quantities" width="100" height="300"></canvas>
    </div>
</div>
<?php 
$this->registerJs(
'
var data = {
    datasets: [
    {
        label : "'.Yii::t('booking', 'Euro').'",
        data: '.json_encode(array_values($amount_datas)).',
        backgroundColor: "'.$color_amount.'",
        barThickness: "flex"
    }
    ],
    labels: '.json_encode(array_keys($amount_datas)).',
};
var options = {
    maintainAspectRatio : false,
    legend: {
        display: true,
        position : "right"
    },
    scales: {
        xAxes: [{ stacked: true }],
        yAxes: [{ 
            stacked: true,
            ticks: {
                beginAtZero: true,
                suggestedMax: 50
            }
        }]
      }
};
var myBarChart = new Chart( $("#bars-amounts"), {
    type: "bar",
    data: data,
    options: options
});

var data = {
    datasets: [
    {
        label : "'.Yii::t('booking', 'Reservations number').'",
        data: '.json_encode(array_values($quantity_datas)).',
        backgroundColor: "'.$color_quantity.'",
        barThickness: "flex"
    },
    ],
    labels: '.json_encode(array_keys($amount_datas)).',
};

var myBarChart = new Chart( $("#bars-quantities"), {
    type: "bar",
    data: data,
    options: options
});
');