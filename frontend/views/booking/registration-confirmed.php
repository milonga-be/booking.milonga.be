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
<?= Yii::t('booking', 'IBAN : BE59 0014 4018 1026') ?><br>
<?= Yii::t('booking', 'BIC : GEBABEBB') ?><br>
<?= Yii::t('booking', 'Alma del Sur ASBL-VZW') ?><br>
<?= Yii::t('booking', 'Rue Michel Zwaab 18') ?><br>
<?= Yii::t('booking', '1080 - Brussels') ?><br>
</p>