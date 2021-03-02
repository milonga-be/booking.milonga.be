<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->title = $model->name;
$this->params['breadcrumbs'] = [
    [
        'label' => $model->event->title,
        'url' => ['event/view', 'uuid' => $model->event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Teachers'),
        'url' => ['teacher/index', 'event_uuid' => $model->event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['teacher/view', 'uuid' => $model->uuid]
    ]
];
?>
<h1><?= $this->title ?></h1>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        // 'event.title', 
        'name', 
    ],
])?>
<p class="text-right">
    <a href="<?= Url::to(['teacher/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
</p>
<?php
$activitiesProvider = new ArrayDataProvider([
    'allModels' => $model->activities,
    'pagination' => false,
]);
?>
<div class="row">
	<div class="col-md-10">
		<h2><?= Yii::t('booking', 'Activities')?></h2>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $activitiesProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
    	[
    		'attribute' => 'title',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->title, ['/activity/view', 'uuid' => $data->uuid]);
    		},
    	],
        // [
        //     'attribute' => 'partner.name',
        //     'format' => 'raw',
        //     'value' => function($data){
        //         return Html::a($data->partner->name, ['/partner/view', 'uuid' => $data->partner->uuid]);
        //     },
        //     'visible' => $model->couple_activity
        // ],
    ]
 ])
?>
