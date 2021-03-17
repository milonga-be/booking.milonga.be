<?php
use yii\helpers\ArrayHelper;
use kartik\number\NumberControl;

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
</div>
