<?php
$this->title = Yii::t('booking', 'Registration Complete').' - '.$event->title;
?>
<h3>
	<?= Yii::t('booking', 'Your booking is complete !') ?>
</h3>
<p>
	<?= Yii::t('booking', 'Your booking reference is : ') ?>
	<strong><?= $booking->uuid ?></strong>
</p>
<h3><?= Yii::t('booking', 'Personal informations') ?></h3>
<table class="table table-striped"> 
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
<table class="table table-striped"> 
<?php
foreach ($booking->participations as $participation) {
	echo '<tr>';
	echo '<td>'.$participation->activity->title.'</td>';
	echo '<td>'.$participation->activity->activityGroup->title.'</td>';
	if($participation->activity->datetime)
		echo 	'<td>'.Yii::$app->formatter->asDatetime($participation->activity->datetime).'</td>';
	else
		echo 	'<td></td>';
	echo '<td>'.Yii::$app->formatter->asCurrency($participation->activity->price).'</td>';
	echo '</tr>';
}

?>
	<tr>
		<td class="total_label" colspan="3"><?=  Yii::t('booking', 'Total')?></td>
		<td class="total"><?= Yii::$app->formatter->asCurrency($booking->total_price) ?></td>
	</tr>
</table>
<h3>
<?= Yii::t('booking', 'Total price') ?> :
<?= Yii::$app->formatter->asCurrency($booking->total_price) ?>
</h3>
<p>
<?= Yii::t('booking', 'The amount must be paid on the following bank account : ') ?><br>
<?= Yii::t('booking', 'IBAN : BE59 0014 4018 1026') ?><br>
<?= Yii::t('booking', 'BIC : GEBABEBB') ?><br>
<?= Yii::t('booking', 'Alma del Sur ASBL-VZW') ?><br>
<?= Yii::t('booking', 'Rue Michel Zwaab 18') ?><br>
<?= Yii::t('booking', '1080 - Brussels') ?><br>
</p>