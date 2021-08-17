<?php
use kartik\widgets\DatePicker;

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd',
        'weekStart' => 1
    ]
];

echo $form->field($model, 'name');
echo $form->field($model, 'validity_start')->widget(DatePicker::classname(), $datepicker_options);
echo $form->field($model, 'validity_end')->widget(DatePicker::classname(), $datepicker_options);
?>