<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->title = $model->summary;
$this->params['breadcrumbs'] = [
    [
        'label' => $model->event->title,
        'url' => ['event/view', 'uuid' => $model->event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Activities'),
        'url' => ['activity/index', 'event_uuid' => $model->event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['activity/view', 'uuid' => $model->uuid]
    ]
];
?>
<h1><?= $this->title ?></h1>
<?php
switch($model->activityGroup->title){
    case 'Salon':
        $attributes = [
            'title',  
            'datetime:datetime', 
        ];
        break;
    case 'Pass':
        $attributes = [
            'title'
        ];
        break;
    default:
        $attributes = [
            'teacher.name', 
            'title', 
            'readableDance', 
            'readableLevel', 
            'price:currency', 
            'datetime:datetime', 
            'couple_activity:boolean', 
            'max_participants', 
        ];

}?>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => $attributes,
])?>
<p class="text-right">
    <a href="<?= Url::to(['activity/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
</p>
<?php
$participationsProvider = new ArrayDataProvider([
    'allModels' => $model->confirmedParticipations,
    'pagination' => false,
]);
?>
<div class="row">
	<div class="col-md-10">
		<h2><?= Yii::t('booking', 'Participants')?></h2>
	</div>
	<!--div class="col-md-2 text-right">
		<a href="<?= Url::to(['participation/create', 'activity_uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Add') ?></a>
	</div-->
</div>
<?= GridView::widget([
    'dataProvider' => $participationsProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
    	[
    		'attribute' => 'booking.name',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->booking->name, ['/booking/view', 'uuid' => $data->booking->uuid]);
    		},
    	],
        [
            'attribute' => 'partner.name',
            'format' => 'raw',
            'value' => function($data){
                return Html::a($data->partner->name, ['/partner/view', 'uuid' => $data->partner->uuid]);
            },
            'visible' => $model->couple_activity
        ],
    ]
 ])
?>
