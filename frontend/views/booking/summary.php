<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\components\PriceManager;

$this->title = Yii::t('booking', 'Registration Summary').' - '.$event->title;

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
			<th><?= Yii::t('booking', 'Price')?></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach($model->activities as $activity){?>
			<tr>
				<td>
					<?= $activity->title ?>
					<?= $form->field($model, 'activities_uuids[]')->hiddenInput(['value' => $activity->uuid])->label(false) ?>
				</td>
				<td><?= $activity->activityGroup->title ?></td>
				<td><?= Yii::$app->formatter->asDatetime($activity->datetime) ?></td>
				<td><?= Yii::$app->formatter->asCurrency($activity->price) ?></td>
			</tr>
		<?php } ?>
		<tr>
			<td class="subtotal_label" colspan="3"><?=  Yii::t('booking', 'Total')?></td>
			<td class="subtotal"><?= Yii::$app->formatter->asCurrency($priceManager->computeUnreducedPrice($model->activities))?></td>
		</tr>
		<?php
		$validReductions = $priceManager->getValidReductions($model->activities);
		foreach($validReductions as $reduction){?>
		<tr>
			<td class="reduction_label" colspan="3"><?=  $reduction->name ?></td>
			<td class="reduction_summary"><?=  $reduction->summary ?></td>
		</tr>
		<?php } ?>
		<?php if(sizeof($validReductions)){ ?>
		<tr>
			<td class="total_label" colspan="3"><?=  Yii::t('booking', 'Total with reductions')?></td>
			<td class="total"><?= Yii::$app->formatter->asCurrency($priceManager->computeFinalPrice($model->activities))?></td>
		</tr>
		<?php }?>
	</tbody>
</table>
<div class="row">
	<div class="col-md-6">
		<h2><?=  Yii::t('booking', 'Your datas')?></h2>
		<?= $form->field($model, 'firstname')?>
		<?= $form->field($model, 'lastname')?>
		<?= $form->field($model, 'email')?>
	</div>
	<? if($model->enablePartnerForm()):?>
	<div class="col-md-6">
		<h2><?=  Yii::t('booking', 'Your partner')?></h2>
		<?= $form->field($model, 'partner_firstname')?>
		<?= $form->field($model, 'partner_lastname')?>
	</div>
	<? endif ?>
</div>
<div class="text-right">
	<button class="btn btn-primary"><?= Yii::t('booking', 'Send')?></button>
</div>
<?php
ActiveForm::end();