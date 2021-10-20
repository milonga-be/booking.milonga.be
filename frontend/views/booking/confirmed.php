<?php
$this->title = Yii::t('booking', 'Registration Complete').' - '.$event->title;
?>
<div class="wrap">
	<?= $this->render('_banner', ['event' => $event]) ?>
    <div class="container">
		<h3>
			<?= Yii::t('booking', 'Your booking is complete !') ?>
		</h3>
		<p>
			<?= Yii::t('booking', 'Your booking reference is : ') ?>
			<strong><?= $model->uuid ?></strong>
		</p>
		<h3><?= Yii::t('booking', 'Personal informations') ?></h3>
		<table class="table table-striped"> 
			<tr>
				<td><?= Yii::t('booking', 'Firstname')?></td>
				<td><?= $model->firstname ?></td>
			</tr>
			<tr>
				<td><?= Yii::t('booking', 'Lastname')?></td>
				<td><?= $model->lastname ?></td>
			</tr>
			<tr>
				<td><?= Yii::t('booking', 'Email')?></td>
				<td><?= $model->email ?></td>
			</tr>
		</table>
		<h3><?= Yii::t('booking', 'Activities') ?></h3>
		<table class="table table-striped"> 
		<?php
		foreach ($model->participations as $participation) {
			echo '<tr>';
			echo '<td>'.$participation->activity->title.'</td>';
			echo '<td>'.$participation->activity->activityGroup->title.'</td>';
			if($participation->activity->datetime)
				echo 	'<td>'.Yii::$app->formatter->asDatetime($participation->activity->datetime).'</td>';
			else
				echo 	'<td></td>';
			echo '<td class="price">'.$participation->activity->getPriceSummary().'</td>';
			echo '</tr>';
		}

		?>
			<tr>
				<td class="subtotal_label" colspan="3"><?=  Yii::t('booking', 'Total')?></td>
				<td class="subtotal"><?= Yii::$app->formatter->asCurrency($priceManager->computeUnreducedPrice($model->activities))?></td>
			</tr>
			<?php
			$validReductions = $priceManager->getValidReductions($model->activities);
			foreach($validReductions as $reduction){?>
			<tr>
				<td class="reduction_label" colspan="3"><?=  $reduction->name ?></td>
				<td class="reduction_summary"><?=  $reduction->summary ?></td>
			</tr>
			<?php } ?>
			<?php if(sizeof($validReductions)){ ?>
			<tr>
				<td class="total_label" colspan="3"><?=  Yii::t('booking', 'Total with reductions')?></td>
				<td class="total"><?= Yii::$app->formatter->asCurrency($priceManager->computeFinalPrice($model->activities))?></td>
			</tr>
			<?php }?>
		</table>
		<h3>
		<?= Yii::t('booking', 'Total price') ?> :
		<?= Yii::$app->formatter->asCurrency($model->total_price) ?>
		</h3>
		<p>
		<?= Yii::t('booking', 'The amount must be paid on the following bank account : ') ?><br>
		<?= Yii::t('booking', 'IBAN : BE59 0014 4018 1026') ?><br>
		<?= Yii::t('booking', 'BIC : GEBABEBB') ?><br>
		<?= Yii::t('booking', 'Alma del Sur ASBL-VZW') ?><br>
		<?= Yii::t('booking', 'Rue Michel Zwaab 18') ?><br>
		<?= Yii::t('booking', '1080 - Brussels') ?><br>
		</p>
	</div>
</div>