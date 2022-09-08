<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\PriceManager;

$this->title = Yii::t('booking', 'Registration Summary').' - '.$event->title;
?>
<div class="wrap">
	<?= $this->render('_banner', ['event' => $event]) ?>
    <div class="container">
<?php

$form = ActiveForm::begin([
	'options' => [],
	'action' => Url::to(['/booking/summary', 'event_uuid' => $event->uuid])
]);

?>
<h1><?=  Yii::t('booking', 'Summary')?></h1>
<h2><?=  Yii::t('booking', 'Selected activities')?></h2>
<table class="table table-striped">
	<thead>
		<tr>
			<th><?= Yii::t('booking', 'Activity')?></th>
			<th><?= Yii::t('booking', 'Type')?></th>
			<th><?= Yii::t('booking', 'Date')?></th>
			<th class="price"><?= Yii::t('booking', 'Price')?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($model->participations as $participation){
			$activity = $participation->activity;
			?>
			<tr>
				<td>
					<?= $activity->title ?>
					<?= $form->field($model, 'activities_with_quantities[]')->hiddenInput(['value' => $activity->uuid.':'.$participation->quantity])->label(false) ?>
				</td>
				<td><?= $activity->activityGroup->title ?></td>
				<td><?= Yii::$app->formatter->asDatetime($activity->datetime) ?></td>
				<td class="price"><?= $participation->getPriceSummary() ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td class="subtotal_label" colspan="3"><?=  Yii::t('booking', 'Total')?></td>
			<td class="subtotal"><?= Yii::$app->formatter->asCurrency($priceManager->computeUnreducedPrice($model->participations))?></td>
		</tr>
		<?php
		$validReductions = $priceManager->getValidReductions($model->participations);
		foreach($validReductions as $reduction){?>
		<tr>
			<td class="reduction_label" colspan="3"><?=  $reduction->name ?></td>
			<td class="reduction_summary"><?=  $reduction->summary ?></td>
		</tr>
		<?php } ?>
		<?php if(sizeof($validReductions)){ ?>
		<tr>
			<td class="total_label" colspan="3"><?=  Yii::t('booking', 'Total with reductions')?></td>
			<td class="total"><?= Yii::$app->formatter->asCurrency($priceManager->computeFinalPrice($model->participations))?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<div class="row">
	<div class="col-md-6">
		<h2><?=  Yii::t('booking', 'Your datas')?></h2>
		<?= $form->field($model, 'role')->radioList(['leader' => Yii::t('booking', 'Leader'), 'follower' => Yii::t('booking', 'Follower')])?>
		<?= $form->field($model, 'firstname')?>
		<?= $form->field($model, 'lastname')?>
		<?= $form->field($model, 'email')?>
	</div>
	<? if($model->enablePartnerForm()):?>
	<div class="col-md-6">
		<h2><?=  Yii::t('booking', 'Your partner')?></h2>
		<?= $form->field($model, 'has_partner')->radioList(['yes' => Yii::t('booking', 'Yes'), 'no' => Yii::t('booking', 'No')])?>
		<?= $form->field($model, 'partner_firstname')?>
		<?= $form->field($model, 'partner_lastname')?>
	</div>
	<? endif ?>
</div>
<div class="row">
	<div class="col-md-6 text-left">
		
	</div>
	<div class="col-md-6 text-right">
		<a href="<?= Url::to(['booking/create', 'event_uuid' => $event->uuid])?>" class="btn btn-danger"><?= Yii::t('booking', 'Back')?></a>
		<button class="btn btn-primary"><?= Yii::t('booking', 'Send')?></button>
	</div>
</div>
<?php
ActiveForm::end();
?>
</div>
</div>