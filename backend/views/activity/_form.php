<?php
use kartik\widgets\DateTimePicker;

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'dd-mm-yyyy hh:ii',
        'weekStart' => 1
    ]
];

echo $form->field($model, 'title');
echo $form->field($model, 'price');
echo $form->field($model, 'datetime')->widget(DateTimePicker::classname(), $datepicker_options);
echo $form->field($model, 'couple_activity')->checkbox();
