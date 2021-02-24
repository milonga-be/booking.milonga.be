<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

$this->title = Yii::t('booking', 'Reservation Detail');
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a href="<?= Url::to(['booking/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
	</div>
</div>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        // 'created_at:datetime', 
        'firstname', 
        'lastname', 
        'email',
        'total_price:currency',
    ],
])?>
<?php
$participationsProvider = new ArrayDataProvider([
    'allModels' => $model->participations,
    'pagination' => false,
]);
?>
<div class="row">
	<div class="col-md-10">
		<h2><?= Yii::t('booking', 'Activities')?></h2>
	</div>
	<div class="col-md-2 text-right">
		<div class="btn-group">
			<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
    			Add <span class="caret"></span>
  			</button>
			<ul class="dropdown-menu">
				<?php $activities = $model->getActivitiesList(); ?>
				<?php foreach ($activities as $uuid => $title) {
					echo '<li><a href="'.Url::to(['participation/create', 'booking_uuid' => $model->uuid, 'activity_uuid' => $uuid]).'">'.$title.'</a></li>';
				}
				?>
			</ul>
		</div>
	</div>
</div>
<?= GridView::widget([
    'dataProvider' => $participationsProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
    	[
    		'attribute' => 'activity.title',
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->activity->title, ['/activity/view', 'uuid' => $data->activity->uuid]);
    		},
    	],
    	[
    		'attribute' => 'partner.name',
    		'format' => 'raw',
    		'value' => function($data){
    			if(isset($data->partner))
    				return Html::a($data->partner->name, ['/partner/update', 'uuid' => $data->partner->uuid]);
    			else
    				return '-';
    		},
    	],
    ]
 ])
?>
