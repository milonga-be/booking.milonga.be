<?php
use yii\grid\GridView;
use yii\helpers\Url;
use yii\helpers\Html;

$this->title = Yii::t('booking', 'Activities');
$this->params['breadcrumbs'] = [
    [
        'label' => $event->title,
        'url' => ['event/view', 'uuid' => $event->uuid]
    ],
    [
        'label' => $this->title,
        'url' => ['activity/index', 'event_uuid' => $event->uuid]
    ]
];
?>
<div class="row">
	<div class="col-md-9">
		<h1><?= $this->title ?></h1>
	</div>
    <div class="col-md-3 text-right">
        <div class="btn-group">
            <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <?= Yii::t('booking', 'New')?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
                <?php foreach ($event->activityGroups as $activityGroup) {
                    echo '<li><a href="'.Url::to(['activity/create', 'event_uuid' => $event->uuid, 'activity_group_uuid' => $activityGroup->uuid]).'">'.$activityGroup->title.'</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<?= GridView::widget([
    'dataProvider' => $provider,
    'filterModel' => $searchModel,
    'layout' => '{items}'.'<a class="export pull-right btn btn-md btn-default" href="'.Url::to(['activity/export-participants', 'event_uuid' => $event->uuid]).'">'.Yii::t('booking', 'Export').'</a>'.'{pager}',
    'tableOptions' => ['class' => 'table table-hover  table-striped'],
    // 'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
    'columns' => [
        // [
        //     'attribute' => 'created_at',
        //     'format' => ['date', 'php:d M'],
        //     'contentOptions' => ['class' => 'hide-xs text-muted'],
        //     'headerOptions' => ['class' => 'hide-xs']
        // ],
    	[
    		'attribute' => 'searchSummary',
            'label' => Yii::t('booking', 'Title'),
    		'format' => 'raw',
    		'value' => function($data){
    			return Html::a($data->summary, ['/activity/view', 'uuid' => $data->uuid], ['title' => $data->title]);
    		},
    	],
        [
            'attribute' => 'datetime',
            'label' => Yii::t('booking', 'Date'),
            'format' => 'datetime'
        ],
        [
            'attribute' => 'activityGroup_title',
            'label' => Yii::t('booking', 'Type'),
            'value' => 'activityGroup.title'
        ],
        [
            'attribute' => 'countParticipants',
            'label' => Yii::t('booking', 'Nr Participants'),
            'value' => function ($data) {
                return $data->countParticipants();
            }
        ],
        [
            'label' => Yii::t('booking', 'Balance H/F'),
            'value' => function ($data) {
                return $data->countLeaders().' / '.$data->countFollowers();
            }
        ],
        [
            'attribute' => '',
            'format' => 'raw',
            'value' => function ($data) {                      
                return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this item ?').'\')" class="text-danger" href="'.Url::to(['activity/delete', 'uuid' => $data->uuid]).'">x</a>';
            },
        ]
    ]
 ])
?>