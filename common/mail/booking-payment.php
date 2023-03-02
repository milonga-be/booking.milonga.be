<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'The payment of your reservation at the {title} with reference {ref} is complete.', ['title' => $booking->event->title, 'ref' => '<strong>'.$booking->reference.'</strong>']) ?>
</p>
<hr>
<p>
	<?= Yii::t('booking', 'Thanks a lot !') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>