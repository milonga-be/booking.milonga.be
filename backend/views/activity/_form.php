<?php
use kartik\widgets\DateTimePicker;
use yii\helpers\ArrayHelper;

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd hh:ii',
        'weekStart' => 1
    ]
];
echo $form->field($model, 'activity_group_id')->dropDownlist(ArrayHelper::map($event->activityGroups, 'id', 'title'));
echo $form->field($model, 'title');
echo $form->field($model, 'price');
echo $form->field($model, 'datetime')->widget(DateTimePicker::classname(), $datepicker_options);
echo $form->field($model, 'couple_activity')->checkbox();
