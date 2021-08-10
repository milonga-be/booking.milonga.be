<?php
use common\components\PriceManager;
?>
<p>
	<?= Yii::t('booking', 'Dear {firstname}', ['firstname' => $booking->firstname]) ?>
</p>
<p>
	<?= Yii::t('booking', 'Thank you for your reservation at the {title}.', ['title' => $booking->event->title]) ?>
	<br>
	<?= Yii::t('booking', 'You can find the reservation summary below :') ?>
</p>
<?php
foreach($booking->activityGroups as $activityGroup){
	echo '<h3>'.$activityGroup->title.'</h3>';
	echo '<table width="100%">';
	foreach($booking->activities as $activity){
		if($activity->activityGroup->id == $activityGroup->id){
			echo '<tr>';
			echo '<td width="15%">'.Yii::$app->formatter->asDatetime($activity->datetime).'</td>';
			echo '<td width="60%">'.$activity->getSummary(75).'</td>';
			echo '<td style="text-align:right;">'.Yii::$app->formatter->asCurrency($activity->price).'</td>';
			echo '</tr>';
		}
	}
	
	echo '</table>';
}
?>
<table width="100%">
	<tr>
		<td width="75%" colspan="2"><h2><?=  Yii::t('booking', 'Total')?></h2></td>
		<td style="text-align:right;"><strong><?= Yii::$app->formatter->asCurrency(PriceManager::computeTotalPrice($booking->activities))?></strong></td>
	</tr>
</table>
<p>
	<?= Yii::t('booking', 'The amount must be paid on the following bank account : ')?><br>
IBAN : BE59 0014 4018 1026<br>
BIC : GEBABEBB<br>
Alma del Sur ASBL-VZW<br>
Rue Michel Zwaab 18<br>
1080 - Brussels
</p>
<p>
	<?= Yii::t('booking', 'Regards,') ?>
	<br>
	<?= Yii::t('booking', 'The BTF Team') ?>
</p>
