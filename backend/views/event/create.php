<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'New Event');
$this->params['breadcrumbs'] = [
	[
		'label' => $this->title,
		'url' => ['event/create']
	]
];
?>
<!--h1><?= $this->title ?></h1-->
<?php
$form = ActiveForm::begin([
	'options' => []
]);

echo $this->render('_form', ['model' => $model, 'form' => $form]);
?>
<p class="text-right buttons">
    <a href="<?= Url::to(['event/index']) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
	<button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Create')?></button>
</p>
<?php
ActiveForm::end();