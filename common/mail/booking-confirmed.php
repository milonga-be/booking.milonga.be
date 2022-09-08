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
	foreach($booking->participations as $participation){
		$activity = $participation->activity;
		if($activity->activityGroup->id == $activityGroup->id){
			echo '<tr>';
			echo '<td width="15%">'.Yii::$app->formatter->asDatetime($activity->datetime).'</td>';
			echo '<td width="60%">'.$activity->getSummary(75).'</td>';
			echo '<td style="text-align:right;">'.$participation->getPriceSummary().'</td>';
			echo '</tr>';
		}
	}
	
	echo '</table>';
}
?>
<table width="100%">
	<tr>
		<td width="75%" colspan="2"><strong><?=  Yii::t('booking', 'Total')?></strong></td>
		<td style="text-align:right;"><strong><?= Yii::$app->formatter->asCurrency($priceManager->computeUnreducedPrice($booking->participations))?></strong></td>
	</tr>
	<?php
	$validReductions = $priceManager->getValidReductions($booking->participations);
	foreach($validReductions as $reduction){?>
	<tr>
		<td style="color:limegreen;" colspan="2"><?=  $reduction->name ?></td>
		<td style="color:limegreen;text-align:right;" ><?=  $reduction->summary ?></td>
	</tr>
	<?php } ?>
	<?php if(sizeof($validReductions)){ ?>
	<tr>
		<td style="font-weight: bold;" colspan="2"><h2 style="margin-top: 0;"><?=  Yii::t('booking', 'Total with reductions')?></h2></td>
		<td style="font-weight: bold;text-align:right;" ><h2 style="margin-top: 0;"><?= Yii::$app->formatter->asCurrency($priceManager->computeFinalPrice($booking->participations))?></h2></td>
	</tr>
	<?php }?>
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
