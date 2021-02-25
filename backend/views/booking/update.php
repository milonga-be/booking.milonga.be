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
        'label' => Yii::t('booking', 'Reservations'),
        'url' => ['booking/index', 'event_uuid' => $model->event->uuid]
    ],
    [
        'label' => $model->name,
        'url' => ['booking/view', 'uuid' => $model->uuid]
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
<div class="row">
    <div class="col-md-8">
        <a onclick="return confirm('<?= Yii::t('booking', 'Do you really want to delete this item ?') ?>');" href="<?= Url::to(['booking/delete', 'uuid' => $model->uuid]) ?>" class="btn btn-danger btn-md"><?= Yii::t('booking', 'Delete')?></a>
    </div>
    <div class="col-md-4 text-right">
        <a href="<?= Url::to(['booking/view', 'uuid' => $model->uuid]) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
        <button class="btn btn-primary btn-md"><?= Yii::t('booking', 'Update')?></button>
    </div>
</div>
<?php
ActiveForm::end();