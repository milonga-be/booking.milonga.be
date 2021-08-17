<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'New Reduction Rule');
$this->params['breadcrumbs'] = [
    [
        'label' => $reduction->event->title,
        'url' => ['event/view', 'uuid' => $reduction->event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Reductions'),
        'url' => ['reduction/index', 'event_uuid' => $reduction->event->uuid]
    ],
    [
        'label' => $reduction->name,
        'url' => ['reduction/index', '' => $reduction->uuid,'event_uuid' => $reduction->event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['reduction-rule/create', 'reduction_uuid' => $reduction->uuid]
    ]
];
?>
<!--h1><?= $this->title ?></h1-->
<?php
$form = ActiveForm::begin([
	'options' => []
]);

echo $this->render('_form', ['model' => $model, 'form' => $form, 'event' => $reduction->event]);
?>
<p class="text-right buttons">
    <a href="<?= Url::to(['reduction/view', 'uuid' => $reduction->uuid]) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
	<button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Create')?></button>
</p>
<?php
ActiveForm::end();