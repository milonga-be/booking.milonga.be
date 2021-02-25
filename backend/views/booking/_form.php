<?php
use kartik\number\NumberControl;

?>
<div class="row">
	<div class="col-md-6">
		<?= $form->field($model, 'firstname')?>
	</div>
	<div class="col-md-6">
		<?= $form->field($model, 'lastname') ?>
	</div>
</div>
<?= $form->field($model, 'email')?>
<?= $form->field($model, 'total_price')->widget(NumberControl::classname(), [
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