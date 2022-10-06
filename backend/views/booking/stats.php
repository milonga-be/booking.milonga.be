<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;
use backend\assets\ChartJsAsset;

ChartJsAsset::register($this);

$this->title = Yii::t('booking', 'Statistics');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Reservations'),
        'url' => ['booking/index', 'event_uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['booking/stats', 'event_uuid' => $event->uuid]
    ]
];
$color_amount = '#F7A90D';
$color_quantity = '#2c4399';
$color_paid = '#59AF2F';
$color_not_paid = '#CBCBCB';
// $paid = 40;
// $not_paid = 60;
$php_ratio_datas = [$paid, $not_paid];
$ratio_datas = json_encode($php_ratio_datas);
?>
<div class="row">
	<div class="col-md-9">
		<h1><?= $this->title ?></h1>
	</div>
</div>
<h4><?= Yii::t('booking', 'Paid / Not Paid')?></h4>
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <canvas id="payments" width="100" height="300"></canvas>
    </div>
</div>
<h4><?= Yii::t('booking', 'Amounts')?></h4>
<div class="row mb-4">
    <div class="col-md-12 text-center">
        <canvas id="bars-amounts" width="100" height="300"></canvas>
    </div>
</div>
<h4><?= Yii::t('booking', 'Number of Reservations')?></h4>
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
        xAxes: [{ 
            stacked: true,
            ticks : {
                display: true
            }
        }],
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

data = {
    datasets: [{
        data: '.$ratio_datas.',
        backgroundColor: [
          "'.$color_paid.'",
          "'.$color_not_paid.'"
        ]
    }],
    // These labels appear in the legend and in the tooltips when hovering different arcs
    labels: [
        "'.Yii::t('booking', 'Paid').'",
        "'.Yii::t('booking', 'Not Paid').'"
    ],
};
var myDoughnutChart = new Chart( $("#payments"), {
    type: "doughnut",
    data: data,
    options: options
});

options.scales.xAxes[0].ticks.display = false;

var data = {
    datasets: [
    {
        label : "'.Yii::t('booking', 'Reservations number').'",
        data: '.json_encode(array_values($quantity_datas)).',
        backgroundColor: "'.$color_quantity.'",
        barThickness: "flex"
    },
    ],
    labels: '.json_encode(array_keys($quantity_datas)).',
};

var myBarChart = new Chart( $("#bars-quantities"), {
    type: "bar",
    data: data,
    options: options
});
');