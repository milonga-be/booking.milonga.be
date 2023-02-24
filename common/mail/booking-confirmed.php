<?php
use common\components\PriceManager;
?>
<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'Thank you for your reservation at the {title}.', ['title' => $booking->event->title]) ?>
	<br><br>
	<?= nl2br(Yii::t('booking', "If you're looking for a comfortable hotel, not expensive and very close to the milongas, the workshops and the city centre, may I suggest you 3 hotels?

Hôtel des colonies : http://www.hotel-des-colonies.com
Progress Hotel : http://www.progresshotel.be
Hotel Siru : http://www.hotelsiru.com

In order to receive a discount, don't forget to mention the code Tango2023"))?><br><br>
	<?= Yii::t('booking', 'You can find the reservation summary here below.') ?>
</p>
<hr>
<?= $this->render('_booking-summary', ['booking' => $booking]) ?>
<hr>
<p>
	<strong><?= Yii::t('booking', 'The amount must be paid on the following bank account : ')?></strong><br>
IBAN : BE69 0689 4697 7378<br>
BIC : GKCCBEBB<br>
asbl Alma del Tango Vzw<br>
Rue Michel Zwaab 18<br>
1080 - Brussels
</p>
<p>
	<?= Yii::t('booking', 'Regards,') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>
