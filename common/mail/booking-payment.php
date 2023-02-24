<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'The payment of your reservation at the {title} with reference {ref} is complete.', ['title' => $booking->event->title, 'ref' => '<strong>'.$booking->reference.'</strong>']) ?>
</p>
<p>
	<?= Yii::t('booking', 'You can find the reservation summary here below.') ?>
</p>
<hr>
<?= $this->render('_booking-summary', ['booking' => $booking]) ?>
<hr>
<p>
	<?= Yii::t('booking', 'Thanks a lot !') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>