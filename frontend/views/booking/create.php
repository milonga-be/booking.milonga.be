<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('booking', 'Registration').' - '.$event->title;
$ajaxUrl = Url::to(['/booking/ajax-calculate-price', 'event_uuid' => $event->uuid]);
?>
<div class="wrap">
	<?= $this->render('_banner', ['event' => $event]) ?>

    <div class="container">
		<div class="row">
			<div class="col-md-8">
<?php
$form = ActiveForm::begin([
	'id' => 'booking-form',
	'enableClientValidation' => false,
	'options' => ['autocomplete' => 'off']
	// 'action' => Url::to(['/booking/registration-summary', 'event_uuid' => $event->uuid])
]);
?>
<?php if($event->closed): ?>
	<h3><?= Yii::t('booking', 'Reservations are closed, enjoy your Festival ! ') ?></h3>
	<p>
		<?= nl2br(Yii::t('booking', 'Text when reservations are closed : {website}', ['website' => '<a href="'.$event->website.'">'.$event->website.'</a>'])) ?>
	</p>
<?php else: ?>
<p>
	<?= nl2br($event->description)?>
</p>
<h5 class="">
	<?= Yii::t('booking', 'Please select the workshop(s) and the pass(es) you wish to reserve, and scroll down to confirm you Reservation') ?>
</h5>
<?php
foreach ($event->activityGroups as $group) {?>
	<h2 style="margin-top: 30px;"><?= $group->title ?></h2>
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
								<td class="activity <?= $activity && in_array($activity->uuid, $model->activities)?'checked':'' ?> <?= $activity && $activity->isFull()?'full':'' ?>" <?php if($activity): ?>data-uuid="<?= $activity->uuid ?>" data-price="<?= $activity->price ?>" data-title="<?= Html::encode((!empty($activity->dance)?$activity->readableDance.' - ':'').$activity->title) ?>"<?php endif; ?>><?php
									if(isset($activity)){
										echo '<div class="form-group">';
										//echo $form->field($model, 'activities['.$activity->uuid.']')->checkbox(['label' => (!empty($activity->dance)?$activity->readableDance.' - ':'').$activity->title, 'value' => 1, 'checked' => false, 'disabled' => $activity->isFull(), 'data-price' => $activity->price]);
										echo '<input type="hidden" value="0" data-price="'.$activity->price.'" data-uuid="'.$activity->uuid.'" name="BookingForm[activities]['.$activity->uuid.']">';
										echo '<label>'. (!empty($activity->dance)?$activity->readableDance.' - ':'').$activity->title.'</label>';
										echo '</div>';
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
					echo '<tr data-price="'.$activity->price.'" data-title="'.Html::encode($activity->title).'"><td class="'.($activity && $activity->isFull()?'full':'').'">';
					// echo $form->field($model, 'activities_uuids[]')->checkbox(['label' => isset($activity->datetime)?'<strong>'.(new \Datetime($activity->datetime))->format('D M j').'</strong> - '.$activity->title:$activity->title, 'value' => $activity->uuid, 'checked' => in_array($activity->uuid, $model->activities_uuids), 'disabled' => $activity->isFull()]);
					echo isset($activity->datetime)?'<strong>'.(new \Datetime($activity->datetime))->format('D M j').'</strong> - '.$activity->title:$activity->title;
					if($activity->isFull()){
						echo '<strong class="text-danger">'.Yii::t('booking', 'FULL').'</strong>';
					}
					echo '</td>';
					$activity_uuid = $activity->uuid;
					$activity_in_model = 
						array_filter($model->activities, 
								function($item) use ($activity_uuid) {
									return substr($item, 0, strlen($activity_uuid)) == $activity_uuid;
								}
						);
					$is_registered = sizeof($activity_in_model);
					$quantity = $is_registered?explode(':', $activity_in_model[0])[1]:0;
					echo '<td class="quantity">
							<div class="input-group">
  								<input type="button" value="-" class="button-minus" data-field="BookingForm[activities]['.$activity->uuid.']" data-price="'.$activity->price.'">
  								<input type="text" step="1" max="" value="'.$quantity.'" data-uuid="'.$activity->uuid.'" name="BookingForm[activities]['.$activity->uuid.']" class="quantity-field '.($is_registered?'active':'').'">
  								<input type="button" value="+" class="button-plus" data-field="BookingForm[activities]['.$activity->uuid.']" data-price="'.$activity->price.'">
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
</div>
<div class="col-md-4">
	<div id="summary-sidebar" class="summary-sidebar">
		<h3><?= Yii::t('booking', 'Your selection') ?></h3>
		<ul id="summary-activities" class="list-unstyled">
		</ul>
		<div id="summary-reductions"></div>
		<hr>
		<div id="summary-total-container">
			<h4><?= Yii::t('booking', 'Total') ?>: <span id="summary-total-price">0.00</span> &euro;</h4>
		</div>
	</div>
</div>
<?php
ActiveForm::end();
$this->registerJs("var ajaxPriceUrl = '{$ajaxUrl}';", \yii\web\View::POS_HEAD);
$this->registerJs(
'
$(".table-activities td.activity").not(".full").on("click",function(e){
	e.preventDefault();
	if($(this).hasClass("checked")){
		$(this).find("input").val(0);
		$(this).removeClass("checked");
	}
	else{
		$(this).find("input").val(1);
		$(this).addClass("checked");
	}
	updateSummary();
});
'
);
$this->registerJs("
function updateSummary() {
    var formData = {
        'BookingForm[promocode]': $('#bookingform-promocode').val(),
        'BookingForm[activities]': {}
    };

    $('.table-activities td.activity.checked').each(function() {
		var uuid = $(this).data('uuid');
		formData['BookingForm[activities]'][uuid] = 1;
    });

    $('input.quantity-field').each(function() {
        var quantity = parseInt($(this).val());
        if (quantity > 0) {
            var uuid = $(this).data('uuid');
            formData['BookingForm[activities]'][uuid] = quantity;
        }
    });

    $.ajax({
        url: ajaxPriceUrl,
        type: 'POST',
        data: $.param(formData),
        dataType: 'json',
        success: function(data) {
            if (data.error) {
                console.error(data.error);
                return;
            }

            $('#summary-activities').html(data.activitiesHtml);

            var reductionsHtml = '';
            if (data.reductions.length > 0) {
                reductionsHtml += '<ul class=\"list-unstyled summary-reductions-list\">';
                data.reductions.forEach(function(reduction) {
                    reductionsHtml += '<li class=\"reduction\">' + reduction.name + '<span class=\"pull-right\">' + reduction.summary + '</span></li>';
                });
                reductionsHtml += '</ul>';
            }
            $('#summary-reductions').html(reductionsHtml);

            if (data.unreducedPrice.toFixed(2) !== data.finalPrice.toFixed(2)) {
                $('#summary-total-container').html('".Yii::t('booking', 'Subtotal') .": <span style=\"text-decoration: line-through;\" class=\"pull-right\">' + data.unreducedPrice.toFixed(2) + ' &euro;</span><br><h4>". Yii::t('booking', 'Total') .": <span id=\"summary-total-price\" class=\"pull-right\">' + data.finalPrice.toFixed(2) + ' &euro;</span></h4>');
            } else {
                $('#summary-total-container').html('<h4>". Yii::t('booking', 'Total') .": <span id=\"summary-total-price\" class=\"pull-right\">' + data.finalPrice.toFixed(2) + ' &euro;</span></h4>');
            }
        }
    });
}

function incrementValue(e) {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var field = parent.find('input[name=\"' + fieldName + '\"]');
  var currentVal = parseInt(field.val(), 10);

  if (!isNaN(currentVal)) {
    field.val(currentVal + 1);
    field.addClass('active');
  } else {
    field.val(0);
    field.removeClass('active');
  }
  updateSummary();
}

function decrementValue(e) {
  e.preventDefault();
  var fieldName = $(e.target).data('field');
  var parent = $(e.target).closest('div');
  var field = parent.find('input[name=\"' + fieldName + '\"]');
  var currentVal = parseInt(field.val(), 10);

  if (!isNaN(currentVal) && currentVal > 0) {
    field.val(currentVal - 1);
    field.addClass('active');
  } else {
    field.val(0);
    field.removeClass('active');
  }
  updateSummary();
}

$('.input-group').on('click', '.button-plus', function(e) {
  incrementValue(e);
});

$('.input-group').on('click', '.button-minus', function(e) {
  decrementValue(e);
});

$('.input-group').on('keydown', '.quantity-field', function(e) {
  updateSummary();
});

$('.input-group').on('change', '.quantity-field', function(e) {
  updateSummary();
});

$('#bookingform-promocode').on('change keyup', function() {
    updateSummary();
});

updateSummary(); // Initial summary calculation
");
?>
		</div>
	</div>
<?php endif; ?>