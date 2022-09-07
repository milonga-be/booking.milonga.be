<?php 
echo $form->field($model, 'role')->radioList(['leader' => Yii::t('booking', 'Leader'), 'follower' => Yii::t('booking', 'Follower')]);
echo $form->field($model, 'has_partner')->radioList(['yes' => Yii::t('booking', 'Yes'), 'no' => Yii::t('booking', 'No')]);
echo $form->field($model, 'partner_firstname');
echo $form->field($model, 'partner_lastname');