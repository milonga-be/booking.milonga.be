<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'Registration').' - '.$event->title;

$form = ActiveForm::begin([
	'options' => [],
	'action' => Url::to(['/booking/registration-summary', 'event_uuid' => $event->uuid])
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
					<strong><?= $activity->title ?></strong>
					<?= $form->field($model, 'activities_uuids[]')->hiddenInput(['value' => $activity->uuid])->label(false) ?>
				</td>
				<td><?= $activity->activityGroup->title ?></td>
				<td><?= Yii::$app->formatter->asDatetime($activity->datetime) ?></td>
				<td><?= Yii::$app->formatter->asCurrency($activity->price) ?></td>
			</tr>
		<?php } ?>
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
	<button class="btn btn-primary"><?= Yii::t('booking', 'Submit')?></button>
</div>
<?php
ActiveForm::end();