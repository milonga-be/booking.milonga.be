<h3>
	<?= Yii::t('booking', 'Your booking is complete !') ?>
</h3>
<p>
	<?= Yii::t('booking', 'Your booking reference is : ') ?>
	<strong><?= $booking->uuid ?></strong>
</p>
<h3><?= Yii::t('booking', 'Personal informations') ?></h3>
<table class="table table-bordered"> 
	<tr>
		<td><?= Yii::t('booking', 'Firstname')?></td>
		<td><?= $booking->firstname ?></td>
	</tr>
	<tr>
		<td><?= Yii::t('booking', 'Lastname')?></td>
		<td><?= $booking->lastname ?></td>
	</tr>
	<tr>
		<td><?= Yii::t('booking', 'Email')?></td>
		<td><?= $booking->email ?></td>
	</tr>
	<tr>
		<td><?= Yii::t('booking', 'Phone')?></td>
		<td><?= $booking->phone ?></td>
	</tr>
</table>
<h3><?= Yii::t('booking', 'Activities') ?></h3>
<table class="table table-bordered"> 
<?php
foreach ($booking->participations as $participation) {
	$datetime = new \Datetime($participation->activity->datetime);
	echo '<tr>';
	if($participation->activity->datetime)
		echo 	'<td>'.$datetime->format('l j M').'</td>';
	else
		echo 	'<td></td>';
	echo '<td>'.$participation->activity->title.'</td>';
	echo '<td>'.round($participation->activity->price, 2).' €</td>';
	echo '</tr>';
}

?>
</table>
<h3>
<?= Yii::t('booking', 'Total price') ?> :
<?= $booking->total_price ?> €
</h3>
<p>
<?= Yii::t('booking', 'The amount must be paid on the following bank account : ') ?><br>
<?= Yii::t('booking', 'IBAN : BE06 0682 4026 8522') ?><br>
<?= Yii::t('booking', 'BIC : GKCCBEBB') ?><br>
<?= Yii::t('booking', 'NOSOTROS ASBL') ?><br>
<?= Yii::t('booking', 'Chaussée d\'Alsemberg 980') ?><br>
<?= Yii::t('booking', '1180 Bruxelles') ?><br>
</p>