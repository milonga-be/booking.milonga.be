<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = Yii::t('booking', 'Update');
$this->params['breadcrumbs'] = [
    [
        'label' => $model->event->title,
        'url' => ['event/view', 'uuid' => $model->event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Activities'),
        'url' => ['activity/index', 'event_uuid' => $model->event->uuid]
    ],
    [
        'label' => $model->title,
        'url' => ['activity/view', 'uuid' => $model->uuid]
    ]
];
?>
<!--h1><?= $this->title ?></h1-->
<?php
$form = ActiveForm::begin([
	'options' => []
]);

echo $this->render('_form', ['model' => $model, 'form' => $form]);
?>
<p class="text-right buttons">
    <a href="<?= Url::to(['activity/view', 'uuid' => $model->uuid]) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
	<button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Update')?></button>
</p>
<?php
ActiveForm::end();