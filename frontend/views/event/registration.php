<?php
use yii\widgets\ActiveForm;

$this->title = Yii::t('booking', 'Registration').' - '.$event->title;

$form = ActiveForm::begin([
	'options' => []
]);

foreach ($event->activityGroups as $group) {?>
	<h2><?= $group->title ?></h2>

<?php
}
?>

<?php
ActiveForm::end();