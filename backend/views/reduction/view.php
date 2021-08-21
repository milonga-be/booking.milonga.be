<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\DetailView;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use common\models\ReductionRule;

$this->title = $model->name;
$this->params['breadcrumbs'] = [
    [
        'label' => $model->event->title,
        'url' => ['event/view', 'uuid' => $model->event->uuid]
    ],
    [
        'label' => Yii::t('booking', 'Reductions'),
        'url' => ['reduction/index', 'event_uuid' => $model->event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['reduction/view', 'uuid' => $model->uuid]
    ]
];
?>
<h1><?= $this->title ?></h1>
<?= DetailView::widget([
    'model' => $model,
    'attributes' => [
        // 'event.title', 
        'name', 
        'validity_start:date', 
        'validity_end:date', 
    ],
])?>
<p class="text-right">
    <a href="<?= Url::to(['reduction/update', 'uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Update') ?></a>
</p>
<hr>
<?php
$rulesProvider = new ArrayDataProvider([
    'allModels' => $model->rules,
    'pagination' => false,
]);
$incompatibleReductionsProvider = new ArrayDataProvider([
    'allModels' => $model->incompatibleReductions,
    'pagination' => false,
]);
?>
<div class="row">
	<div class="col-md-10">
		<h2><?= Yii::t('booking', 'Rules')?></h2>
	</div>
    <div class="col-md-2 text-right">
        <a href="<?= Url::to(['reduction-rule/create', 'reduction_uuid' => $model->uuid]) ?>" class="btn btn-md btn-default"><?= Yii::t('booking', 'Add') ?></a>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $rulesProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
    	[
            'attribute' => 'lower_limit',
            'format' => 'raw',
            'value' => function($model){
                if($model->lower_limit == $model->higher_limit)
                    return Html::a(Yii::t('booking','{higher_limit} {title}', ['higher_limit' => $model->higher_limit, 'title' => $model->activityGroup->title]), ['reduction-rule/update', 'uuid' => $model->uuid]);
                else    
                    return Html::a(Yii::t('booking','Between {lower_limit} & {higher_limit} {title}', ['lower_limit' => $model->lower_limit, 'higher_limit' => $model->higher_limit, 'title' => $model->activityGroup->title]), ['reduction-rule/update', 'uuid' => $model->uuid]);
            }
        ],
        [
            'attribute' => 'value',
            'value' => function($model){
                return Yii::$app->formatter->asCurrency($model->value);
            }
        ],
        [
            'attribute' => 'type',
            'value' => function($model){
                return ReductionRule::getTypesList()[$model->type];
            }
        ]
    ]
 ])
?>
<div class="row">
    <div class="col-md-10">
        <h2><?= Yii::t('booking', 'Incompatible Reductions')?></h2>
    </div>
    <div class="col-md-2 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= Yii::t('booking', 'New')?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php 
                $reductions = $model->event->reductions; 
                $incompatibleReductionsUuids = ArrayHelper::getColumn($model->incompatibleReductions, 'uuid');
                ?>
                <?php foreach ($reductions as $reduction) {
                    if($reduction->uuid != $model->uuid && !in_array($reduction->uuid, $incompatibleReductionsUuids))
                        echo '<li><a href="'.Url::to(['reduction-incompatibility/create', 'incompatible_reduction_uuid' => $reduction->uuid, 'reduction_uuid' => $model->uuid]).'">'.$reduction->name.'</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $incompatibleReductionsProvider,
    'showHeader'=> false,
    'layout' => '{items}{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
        [
            'attribute' => 'name',
            'format' => 'raw',
            'value' => function($model){
                return Html::a($model->name, ['reduction/view', 'uuid' => $model->uuid]);
            }
        ],
        [
            'attribute' => 'Delete',
            'format' => 'raw',
            'value' => function ($data) use ($model)  {                      
                return '<a class="text-danger" href="'.Url::to(['reduction-incompatibility/delete', 'incompatible_reduction_uuid' => $data->uuid, 'reduction_uuid' => $model->uuid]).'">x</a>';
            },
        ],
    ]
 ])
?>
