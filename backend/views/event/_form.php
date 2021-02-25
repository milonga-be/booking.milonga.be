<?php
use kartik\widgets\DatePicker;

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-dd-mm',
        'weekStart' => 1
    ]
];

echo $form->field($model, 'title');
echo $form->field($model, 'start_date')->widget(DatePicker::classname(), $datepicker_options);
echo $form->field($model, 'end_date')->widget(DatePicker::classname(), $datepicker_options);
