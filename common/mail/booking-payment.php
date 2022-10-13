<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'The payment of your reservation at the {title} with reference {ref} has been completed.', ['title' => $booking->event->title, 'ref' => '<strong>'.$booking->reference.'</strong>']) ?>
</p>
<p>
	<?= Yii::t('booking', 'Thanks a lot !') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>