<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'We would like to remind you to pay your reservation to the {title} with reference {ref}', ['title' => $booking->event->title, 'ref' => '<strong>'.$booking->reference.'</strong>']) ?>
</p>
<p>
	<?= Yii::t('booking', 'The amount due is {amount}', ['amount' => '<strong>'.Yii::$app->formatter->asCurrency($booking->getAmountDue()).'</strong>']) ?>
<p>
	<?= nl2br($booking->event->payment_instructions) ?>
</p>
<p>
	<?= Yii::t('booking', 'Thanks a lot !') ?>
	<br>
	<?= Yii::t('booking', 'The {title} Team', ['title' => $booking->event->title]) ?>
</p>