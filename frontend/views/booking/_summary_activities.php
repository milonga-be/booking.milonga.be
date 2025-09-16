<?php
use yii\helpers\Html;

foreach ($participations as $participation) {
    $activity = $participation->activity;
    $activityTotal = $participation->quantity * $activity->price;
    echo '<li title="'.$activity->title.'">' . $participation->quantity . ' x ' . Html::encode($activity->getSummary(30)) . ' <span class="pull-right">' . Yii::$app->formatter->asDecimal($activityTotal, 2) . ' &euro;</span></li>';
}
?>