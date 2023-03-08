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
<?= $this->render('_tabs', ['event' => $event, 'selected' => 'index']) ?>
<div class="row mt-2 mb-2">
	<div class="col-md-4">
		<h1><?= Yii::t('booking', 'Total : ') ?><?= Yii::$app->formatter->asCurrency($total) ?></h1>
	</div>
    <div class="col-md-4 text-center">
    </div>
	<div class="col-md-4 text-right">
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
        [
            'attribute' => 'name_search',
            'format' => 'raw',
            'label' => Yii::t('booking', 'Name'),
            'value' => function($data){
                return Html::a($data->name, ['/booking/view', 'uuid' => $data->uuid]);
            },
        ],
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
                if(isset($data->partnerBooking)){
                    return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this reservation {ref1} and the one of the partner {ref2} ?', ['ref1' => $data->getReference(), 'ref2' => $data->partnerBooking->getReference()]).'\')" class="text-danger" href="'.Url::to(['booking/delete', 'uuid' => $data->uuid]).'">x</a>';
                }else{
                    return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this reservation ?').'\')" class="text-danger" href="'.Url::to(['booking/delete', 'uuid' => $data->uuid]).'">x</a>';
                }
            },
        ],
    ]
 ])
?>