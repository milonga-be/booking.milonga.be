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
<p class="text-right buttons">
    <a href="<?= Url::to(['event/index']) ?>" class="btn btn-secondary btn-lg"><?= Yii::t('booking', 'Cancel')?></a>
	<button class="btn btn-primary btn-lg"><?= Yii::t('booking', 'Update')?></button>
</p>
<?php
ActiveForm::end();