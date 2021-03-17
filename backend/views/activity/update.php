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
        'label' => $model->summary,
        'url' => ['activity/view', 'uuid' => $model->uuid]
    ]
];
?>
<!--h1><?= $this->title ?></h1-->
<?php
$form = ActiveForm::begin([
	'options' => []
]);

echo $this->render('_form_'.strtolower($type), ['model' => $model, 'form' => $form, 'event' => $event]);
?>
<div class="row">
    <div class="col-md-8">
        <a onclick="return confirm('<?= Yii::t('booking', 'Do you really want to delete this item ?') ?>');" href="<?= Url::to(['activity/delete', 'uuid' => $model->uuid]) ?>" class="btn btn-danger btn-md"><?= Yii::t('booking', 'Delete')?></a>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= Url::to(['activity/view', 'uuid' => $model->uuid]) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
        <button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Update')?></button>
    </div>
</div>
<?php
ActiveForm::end();