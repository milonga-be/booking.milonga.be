<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin(['options' => []]);

$this->registerJs(
'$("input[type=checkbox]").on("click",function(e){
	var url = "'.Url::to(['event/compute-price', 'event_id' => $event->id]).'";
	var data = "&activities=";

	$("input[type=checkbox]:checked").each(function() {
		data+=$(this).val() + ",";	
	});


	$.ajax({
        url : url + data,
        type : "GET",
    }).done(function(data){
        $("#total-price").text(data);
    });

});'
);
?>
<p class="bg-danger promotions">
	<strong><?= Yii::t('booking', 'Promotions') ?></strong><br>
	<?php
		foreach ($event->reductions as $reduction) {
			echo '&bull; '.Yii::t('booking', $reduction->description).'<br>';
		}
	?>
</p>
<?php
foreach($event->activityGroups as $group){ 
	$maxLine = 1;
	foreach ($group->activitiesByDates as $activitiesArray) {
		if(sizeof($activitiesArray) > $maxLine){
			$maxLine = sizeof($activitiesArray);
		}
	}
	$pctCell = round(100 / $maxLine, 2, PHP_ROUND_HALF_DOWN);
?>
	<h3><?= Yii::t('booking', $group->title) ?></h3>
	<p class="activity_group_description text-muted text-left">
		<?= nl2br(Yii::t('booking', $group->introduction))?>
	</p>
	<table class="table table-bordered activity_group_table">
	<?php
	foreach ($group->activitiesByDates as $date => $activitiesArray) {
		if(!$date){
			echo '<tr>';
			foreach ($activitiesArray as $activity) {
				echo '<td>';
				echo 	'<div class="checkbox col-md-10">';
				echo 		'<label><input type="checkbox" class="form-check-input" name="activity[]" value="'.$activity->id.'"> <strong>'.Yii::t('booking', $activity->title).'</strong> <span class="text-muted">'.Yii::t('booking', $activity->description).'</span></label>';
				echo 	'</div>';
				echo 	'<div class="col-md-2 price text-right">'.round($activity->price, 2).'€</div>';
				echo '</td>';
			}
			echo '</tr>';
		}else{
			$datetime = new \Datetime($date);
			echo '<tr><td>'.Yii::t('booking', $datetime->format('l')).'<br>'.$datetime->format('j').' '.Yii::t('booking', $datetime->format('M')).'</td>';
			foreach ($activitiesArray as $activity) {
				echo '<td style="width:'.$pctCell.'%;">';
				echo 	'<div class="checkbox col-md-10">';
				echo 		'<label><input type="checkbox" class="form-check-input" name="activity[]" value="'.$activity->id.'"> <em class="text-muted activity_hour">'.$activity->datetimeObject->format('G:i').'</em> <strong>'.$activity->title.'</strong></label>';
				echo 	'</div>';
				echo 	'<div class="col-md-2 price text-right">'.round($activity->price, 2).'€</div>';
				echo '</td>';
			}
			echo '</tr>';
		}
		
	}
	?>
	</table>
	<p class="activity_group_description text-muted text-left">
		<?= nl2br(Yii::t('booking', $group->description))?>
	</p>
<?php
}
?>
<div class="row" style="padding-top:10px;padding-bottom:10px;">
	<div class="col-md-10"><h3><?= Yii::t('booking', 'Total price') ?></h3></div>
	<div class="col-md-2 text-right"><h3 id="total-price">0 €</h3></div>
</div>
<hr/>
<div class="row">
	<div class="col-md-6">
		<h3><?= Yii::t('booking', 'Personal informations') ?></h3>

		<?= $form->field($participant, 'firstname') ?>
		<?= $form->field($participant, 'lastname') ?>
		<?= $form->field($participant, 'email') ?>
		<?= $form->field($participant, 'phone') ?>
	</div>
	<div class="col-md-6">
		<h3><?= Yii::t('booking', 'Partner') ?></h3>

		<?= $form->field($partner, 'firstname') ?>
		<?= $form->field($partner, 'lastname') ?>
	</div>
</div>
<p class="text-right">
	<button class="btn btn-lg btn-danger"><?= Yii::t('booking', 'Submit') ?></button>
</p>
<?php
ActiveForm::end();
?>