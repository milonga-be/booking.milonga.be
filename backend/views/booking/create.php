<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'New Reservation');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Reservations'),
        'url' => ['booking/index', 'event_uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['activity/create', 'event_uuid' => $event->uuid]
    ]
];
?>
<h1><?= $this->title ?></h1>
<?php
$form = ActiveForm::begin([
	'options' => []
]);

echo $this->render('_form', ['model' => $model, 'form' => $form]);
?>
<p class="text-right buttons">
    <a href="<?= Url::to(['booking/index', 'event_uuid' => $event->uuid]) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
	<button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Create')?></button>
</p>
<?php
ActiveForm::end();