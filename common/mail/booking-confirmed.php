<?php
use common\components\PriceManager;
?>
<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'Thank you for your reservation at the {title}.', ['title' => $booking->event->title]) ?>
	<br><br>
	<?= Yii::t('booking', 'You can find the reservation summary here below.') ?>
</p>
<hr>
<?= $this->render('_booking-summary', ['booking' => $booking]) ?>
<hr>
<p>
	<?= nl2br($booking->event->payment_instructions) ?>
</p>
<p>
	<?= Yii::t('booking', 'Regards,') ?>
	<br>
	<?= Yii::t('booking', 'The {title} Team', ['title' => $booking->event->title]) ?>
</p>
