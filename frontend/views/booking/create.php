<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'Registration').' - '.$event->title;
?>
<div class="wrap">
	<?= $this->render('_banner', ['event' => $event]) ?>

    <div class="container">
<?php
$form = ActiveForm::begin([
	'options' => [],
	// 'action' => Url::to(['/booking/registration-summary', 'event_uuid' => $event->uuid])
]);
?>
<?php if($event->closed): ?>
	<h3><?= Yii::t('booking', 'Reservations are closed, enjoy your Festival ! ') ?></h3>
	<p>
		<?= nl2br(Yii::t('booking', 'Text when reservations are closed : {website}', ['website' => '<a href="https://brusselstangofestival.com/reservation/practical/">https://www.brusselstangofestival.com/</a>'])) ?>
	</p>
<?php else: ?>
<h4>
	<?= nl2br(Yii::t('booking', 'Booking Intro Text'))?>
</h4>
<div class="row bg-info">
	<div class="col-md-6">
		<h4><?= Yii::t('booking', 'Booking Column 1 Title')?></h4>
		<p><?= nl2br(Yii::t('booking', 'Booking Column 1 Text'))?></p>
	</div>
	<div class="col-md-6">
		<h4><?= Yii::t('booking', 'Booking Column 2 Title')?></h4>
		<p><?= nl2br(Yii::t('booking', 'Booking Column 2 Text'))?></p>
	</div>
</div>
<h4 class="">
	<?= Yii::t('booking', 'Please select the workshop(s) and the pass(es) you wish to reserve, and scroll down to confirm you Reservation') ?>
</h4>
<?php
foreach ($event->activityGroups as $group) {?>
	<h2><?= $group->title ?></h2>
	<?php
		switch ($group->display) {
			case 'grid':
				// Preparing the grid lines
				$days = [];
				foreach ($group->activities as $activity) {
					if(isset($activity->datetime) && isset($activity->teacher)){
						$date = substr($activity->datetime, 0, 10);
						$hour = substr($activity->datetime, 11, 5);
						// echo $date;
						// echo $hour;
						if(!isset($days[$date])){
							// Creating the new day
							$days[$date] = [
								$hour => []
							];
							
						}
						if(!isset($days[$date][$hour])){
							foreach ($event->teachers as $teacher) {
								$days[$date][$hour][$teacher->name] = null;
							}
						}
						$days[$date][$hour][$activity->teacher->name] = $activity;
					}
				}
				// var_dump($days);
				ksort($days);
				foreach ($days as $date => $hours) {
					ksort($days[$date]);
				}
				?>
				<table class="table table-striped table-activities">
					<thead>
						<tr>
							<th class="hidden-xs hidden-s"><?= Yii::t('booking', 'Day') ?></th>
							<th><?= Yii::t('booking', 'Time') ?></th>
							<?php foreach ($event->teachers as $teacher) {?>
							<th><?= $teacher->name ?></th>
							<?php }?>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($days as $date => $day) {
							$first_day = true;
							?>
							<?php foreach ($day as $hour => $teachers) {?>
							<tr>
								<td class="hidden-xs hidden-s day"><strong><?= $first_day?(new \Datetime($date))->format('D M j'):'' ?></strong></td>
								<td class="hidden-xs hidden-s"><?= $hour ?></td>
								<td class="visible-xs visible-s">
									<? if($first_day):?><strong><?= (new \Datetime($date))->format('D')?></strong><br><?endif;?>
									<?= $hour ?>
								</td>
								<?php foreach ($teachers as $teacher_name => $activity) {?>
								<td class="activity <?= $activity && $activity->isFull()?'full':'' ?>"><?php
									if(isset($activity)){
										echo $form->field($model, 'activities_uuids[]')->checkbox(['label' => (!empty($activity->dance)?$activity->readableDance.' - ':'').$activity->title, 'value' => $activity->uuid, 'checked' => in_array($activity->uuid, $model->activities_uuids), 'disabled' => $activity->isFull()]);
										if($activity->isFull()){
											echo '<strong class="text-danger">'.Yii::t('booking', 'FULL').'</strong>';
										}
									} ?>
								</td>
								<?php } 
								$first_day = false;
								?>
							</tr>
							<?php } ?>
						<?php } ?>
					</tbody>
				</table>
				<?php
				break;
			
			default: 
				echo '<table class="table table-striped table-activities">';
				foreach ($group->activities as $activity) {
					echo '<tr><td class="'.($activity && $activity->isFull()?'full':'').'">';
					// echo $form->field($model, 'activities_uuids[]')->checkbox(['label' => isset($activity->datetime)?'<strong>'.(new \Datetime($activity->datetime))->format('D M j').'</strong> - '.$activity->title:$activity->title, 'value' => $activity->uuid, 'checked' => in_array($activity->uuid, $model->activities_uuids), 'disabled' => $activity->isFull()]);
					echo isset($activity->datetime)?'<strong>'.(new \Datetime($activity->datetime))->format('D M j').'</strong> - '.$activity->title:$activity->title;
					if($activity->isFull()){
						echo '<strong class="text-danger">'.Yii::t('booking', 'FULL').'</strong>';
					}
					echo '</td>';
					echo '<td class="quantity">
							<div class="input-group">
								<input id="'.$activity->uuid.'" type="hidden" data-uuid="'.$activity->uuid.'" name="BookingForm[activities_with_quantities][]" value="'.$activity->uuid.':0"/>
  								<input type="button" value="-" class="button-minus" data-field="quantity_'.$activity->uuid.'">
  								<input type="text" step="1" max="" value="0" data-uuid="'.$activity->uuid.'" name="quantity_'.$activity->uuid.'" class="quantity-field">
  								<input type="button" value="+" class="button-plus" data-field="quantity_'.$activity->uuid.'">
							</div>
						</td>';
					echo '</tr>';
				}
				echo '</table>';
				break;
		}
	?>
<?php
}
?>
<h4><?= Yii::t('booking', 'You have a promocode ? Enter it here. Leave empty if not.')?></h4>
<div class="row bg-info promocode">
	<div class="col-md-4">
		<?= $form->field($model, 'promocode')->label(false)?>
	</div>
</div>
<h4 class="text-right">
	<?= Yii::t('booking', 'You will be presented a summary of your Reservation at the next step before final confirmation') ?>
</h4>
<div class="text-right">
	<button class="btn btn-primary btn-lg"><?= Yii::t('booking', 'Continue')?></button>
</div>
<?php
ActiveForm::end();
$this->registerJs(
'
$(".table-activities td.activity").not(".full").on("click",function(e){
	e.preventDefault();
	if($(this).hasClass("checked")){
		$(this).find("input[type=checkbox]").prop("checked", false);
		$(this).removeClass("checked");
	}
	else{
		$(this).find("input[type=checkbox]").prop("checked", true);
		$(this).addClass("checked");
	}
});
'
);
$this->registerJs("
function incrementValue(e) {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var field = parent.find('input[name=\"' + fieldName + '\"]');
  var currentVal = parseInt(field.val(), 10);

  if (!isNaN(currentVal)) {
    field.val(currentVal + 1);
  } else {
    field.val(0);
  }
  var uuid = field.data('uuid');
  $('#'+uuid+'').val(uuid+':'+field.val());
}

function decrementValue(e) {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var field = parent.find('input[name=\"' + fieldName + '\"]');
  var currentVal = parseInt(field.val(), 10);

  if (!isNaN(currentVal) && currentVal > 0) {
    field.val(currentVal - 1);
  } else {
    field.val(0);
  }
  var uuid = field.data('uuid');
  $('#'+uuid+'').val(uuid+':'+field.val());
}

$('.input-group').on('click', '.button-plus', function(e) {
  incrementValue(e);
});

$('.input-group').on('click', '.button-minus', function(e) {
  decrementValue(e);
});

$('.input-group').on('keydown', '.quantity-field', function(e) {
  var uuid = $(this).data('uuid');
  $('#'+uuid+'').val(uuid+':'+$(this).val());
});
")
?>
	</div>
</div>
<?php endif; ?>