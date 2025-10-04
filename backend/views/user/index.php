<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\models\User;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <div class="row">
        <div class="col-md-10">
            <h1><?= $this->title ?></h1>
        </div>
        <div class="col-md-2 text-right">
            <a class="btn btn-md btn-default" href="<?= Url::to(['/user/create'])?>"><?= Yii::t('booking', 'New')?></a>
        </div>
    </div>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'layout' => '{items}{pager}',
        'tableOptions' => ['class' => 'table table-hover  table-striped'],
        'formatter' => ['class' => 'yii\i18n\Formatter', 'nullDisplay' => ''],
        'columns' => [
            [
                'attribute' => 'email',
                'format' => 'raw',
                'value' => function($data){
                    return Html::a($data->email, ['/user/view', 'uuid' => $data->uuid]);
                },
            ],
            [
                'attribute' => 'role',
                'filter' => User::getRoles(),
                'value' => function($data) { return ucfirst($data->role); }
            ],
             [
            'attribute' => '',
            'format' => 'raw',
            'value' => function ($data) {                      
                return '<a onclick="return confirm(\''.Yii::t('booking', 'Do you really want to delete this item ?').'\')" class="text-danger" href="'.Url::to(['user/delete', 'uuid' => $data->uuid]).'">x</a>';
            },
        ]
        ],
    ]); ?>
</div>