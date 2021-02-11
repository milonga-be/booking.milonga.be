<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\widgets\DatePicker;

$form = ActiveForm::begin([
	'options' => []
]);

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy hh:ii',
        'weekStart' => 1
    ]
];

echo $form->field($model, 'title');
echo $form->field($model, 'start_date')->widget(DatePicker::classname(), $datepicker_options);
echo $form->field($model, 'end_date')->widget(DatePicker::classname(), $datepicker_options);
?>
<p class="text-right buttons">
    <a href="<?= Url::to(['event/index']) ?>" class="btn btn-secondary btn-lg"><?= Yii::t('booking', 'Cancel')?></a>
	<button class="btn btn-primary btn-lg"><?= Yii::t('booking', 'Create')?></button>
</p>
<?php
ActiveForm::end();