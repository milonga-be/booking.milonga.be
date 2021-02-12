<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('booking', 'Your events');
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a class="btn btn-lg btn-primary" href="<?= Url::to(['/event/create'])?>"><?= Yii::t('booking', 'Create')?></a>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $searchModel,
    'tableOptions' => ['class' => 'table table-hover'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
    	[
    		'attribute' => 'title',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->title, ['/event/view', 'uuid' => $data->uuid]);
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