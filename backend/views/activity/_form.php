<?php
use kartik\widgets\DateTimePicker;
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd hh:ii',
        'weekStart' => 1
    ]
];
echo $form->field($model, 'activity_group_id')->dropDownlist(ArrayHelper::map($event->activityGroups, 'id', 'title'));
echo $form->field($model, 'title');
?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'price')->widget(NumberControl::classname(), [
		    'maskedInputOptions' => [
		        'prefix' => '',
		        'suffix' => ' €',
		        'allowMinus' => false,
		        'groupSeparator' => '.',
		        'radixPoint' => ',',
		        'rightAlign' => false,
		        'digits' => 2
		    ],
		])?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'datetime')->widget(DateTimePicker::classname(), $datepicker_options) ?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'max_participants')?>
	</div>
</div>
<?php
echo $form->field($model, 'couple_activity')->checkbox();
