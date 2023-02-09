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
<h2><?= Yii::t('booking', 'Invoice BTF {ref}', ['ref' => $booking->reference]) ?></h2>
<hr>
<?php
foreach($booking->activityGroups as $activityGroup){
	echo '<h3>'.$activityGroup->title.'</h3>';
	echo '<table width="100%">';
	foreach($booking->participations as $participation){
		$activity = $participation->activity;
		if($activity->activityGroup->id == $activityGroup->id){
			echo '<tr>';
			echo '<td width="15%">'.(isset($activity->datetime)?Yii::$app->formatter->asDatetime($activity->datetime):'-').'</td>';
			echo '<td width="60%">'.$activity->getSummary(75).'</td>';
			echo '<td style="text-align:right;">'.$participation->getPriceSummary().'</td>';
			echo '</tr>';
		}
	}
	
	echo '</table>';
}
?>
<table width="100%">
	<?php 
	$validReductions = $priceManager->getValidReductions($booking);
	?>
	<tr>
		<td width="75%" colspan="2"><strong><?=  Yii::t('booking', sizeof($validReductions)?'Total without reductions':'Total')?></strong></td>
		<td style="text-align:right;"><strong><?= Yii::$app->formatter->asCurrency($priceManager->computeUnreducedPrice($booking->participations))?></strong></td>
	</tr>
	<?php
	
	foreach($validReductions as $reduction){?>
	<tr>
		<td style="color:limegreen;" colspan="2"><?=  $reduction->name ?></td>
		<td style="color:limegreen;text-align:right;" ><?=  $reduction->summary ?></td>
	</tr>
	<?php } ?>
	<?php if(sizeof($validReductions)){ ?>
	<tr>
		<td style="font-weight: bold;" colspan="2"><h2 style="margin-top: 0;"><?=  Yii::t('booking', 'Total with reductions')?></h2></td>
		<td style="font-weight: bold;text-align:right;" ><h2 style="margin-top: 0;"><?= Yii::$app->formatter->asCurrency($priceManager->computeFinalPrice($booking))?></h2></td>
	</tr>
	<?php }?>
</table>
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
