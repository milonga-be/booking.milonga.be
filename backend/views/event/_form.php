<?php
use kartik\widgets\DatePicker;

$datepicker_options = [
	'pluginOptions' => [
        'autoclose'=>true,
        'format' => 'yyyy-mm-dd',
        'weekStart' => 1
    ]
];

echo $form->field($model, 'title');
echo $form->field($model, 'start_date')->widget(DatePicker::classname(), $datepicker_options);
echo $form->field($model, 'end_date')->widget(DatePicker::classname(), $datepicker_options);
echo $form->field($model, 'bannerFile')->fileInput(['class' => 'form-control']);
if($model->banner){
    echo '<p>';
    echo '  <img class="banner-preview" src="'.\Yii::getAlias('@web').'/../../frontend/web/uploads/'.$model->banner.'">';
    echo '</p>';
}
