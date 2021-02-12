<?php
use yii\helpers\Url;
use yii\widgets\DetailView;
use yii\grid\GridView;

$this->title = Yii::t('booking', 'Event Detail');
?>
<div class="row">
	<div class="col-md-10">
		<h1><?= $this->title ?></h1>
	</div>
	<div class="col-md-2 text-right">
		<a href="<?= Url::to(['event/update', 'uuid' => $model->uuid]) ?>" class="btn btn-lg btn-primary"><?= Yii::t('booking', 'Update') ?></a>
	</div>
</div>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        'title', 
        'start_date:datetime', // creation date formatted as datetime
        'end_date:datetime', // creation date formatted as datetime
    ],
])?>
<div class="row">
	<div class="col-md-6">
		<h2><?= Yii::t('booking', 'Last Reservations')?></h2>

	</div>
	<div class="col-md-6">
		<h2><?= Yii::t('booking', 'Payment balance')?></h2>
	</div>
</div>
<h2><?= Yii::t('booking', 'Activities')?></h2>
