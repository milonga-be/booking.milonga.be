<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'Update Event');
?>
<h1><?= $this->title ?></h1>
<?php
$form = ActiveForm::begin([
	'options' => []
]);

echo $this->render('_form', ['model' => $model, 'form' => $form]);
?>
<div class="row">
	<div class="col-md-8">
		<a href="<?= Url::to(['event/view', 'uuid' => $model->uuid]) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
	</div>
	<div class="col-md-4 text-right">
		<a href="<?= Url::to(['event/delete', 'uuid' => $model->uuid]) ?>" class="btn btn-danger btn-md"><?= Yii::t('booking', 'Delete')?></a>
		<button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Update')?></button>
	</div>
</div>
<?php
ActiveForm::end();