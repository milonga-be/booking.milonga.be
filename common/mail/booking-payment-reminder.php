<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'We would like to remind you to pay your reservation to the {title} with reference {ref}', ['title' => $booking->event->title, 'ref' => '<strong>'.$booking->reference.'</strong>']) ?>
</p>
<p>
	<?= Yii::t('booking', 'The amount due is {amount}', ['amount' => '<strong>'.Yii::$app->formatter->asCurrency($booking->getAmountDue()).'</strong>']) ?>
<p>
	<?= Yii::t('booking', 'This must be paid on the following bank account : ')?><br>
IBAN : BE59 0014 4018 1026<br>
BIC : GEBABEBB<br>
Alma del Sur ASBL-VZW<br>
Rue Michel Zwaab 18<br>
1080 - Brussels
</p>
<p>
	<?= Yii::t('booking', 'Thanks a lot !') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>