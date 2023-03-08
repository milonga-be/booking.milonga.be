<?php
use common\models\Payment;

echo $form->field($model, 'amount');
echo $form->field($model, 'type')->dropDownlist($model->getTypesList());