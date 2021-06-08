<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'Registration').' - '.$event->title;

$form = ActiveForm::begin([
	'options' => [],
	// 'action' => Url::to(['/booking/registration-summary', 'event_uuid' => $event->uuid])
]);

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
							<th><?= Yii::t('booking', 'Day') ?></th>
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
								<td class="day"><strong><?= $first_day?(new \Datetime($date))->format('D M j'):'' ?></strong></td>
								<td><?= $hour ?></td>
								<?php foreach ($teachers as $teacher_name => $activity) {?>
								<td class="activity"><?php
									if(isset($activity)){
										echo $form->field($model, 'activities_uuids[]')->checkbox(['label' => $activity->title, 'value' => $activity->uuid, 'checked' => in_array($activity->uuid, $model->activities_uuids)]);
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
					echo '<tr><td class="activity">';
					echo $form->field($model, 'activities_uuids[]')->checkbox(['label' => isset($activity->datetime)?'<strong>'.(new \Datetime($activity->datetime))->format('D M j').'</strong> - '.$activity->title:$activity->title, 'value' => $activity->uuid, 'checked' => in_array($activity->uuid, $model->activities_uuids)]);
					echo '</td></tr>';
				}
				echo '</table>';
				break;
		}
	?>
<?php
}
?>
<div class="text-right">
	<button class="btn btn-primary"><?= Yii::t('booking', 'Submit')?></button>
</div>
<?php
ActiveForm::end();
$this->registerJs(
'
$(".table-activities td.activity").on("click",function(e){
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