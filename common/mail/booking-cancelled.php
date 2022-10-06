<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'Your reservation at the {title} with reference {ref} has been successfuly cancelled.', ['title' => $booking->event->title, 'ref' => '<strong>'.$booking->reference.'</strong>']) ?>
</p>
<p>
	<?= Yii::t('booking', 'Regards,') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>