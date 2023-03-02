<?php
use yii\helpers\Url;

?>
<ul class="nav nav-tabs">
  <li role="presentation" class="<?= $selected == 'index'?'active':'' ?>"><a href="<?= Url::to(['booking/index', 'event_uuid'=> $event->uuid]) ?>">Reservations</a></li>
  <li role="presentation" class="<?= $selected == 'cancelled-list'?'active':'' ?>"><a href="<?= Url::to(['booking/cancelled-list', 'event_uuid'=> $event->uuid]) ?>">Cancelled</a></li>
</ul>