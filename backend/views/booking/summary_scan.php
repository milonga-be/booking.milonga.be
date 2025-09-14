<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

?>

<div class="booking-summary">
    <?php if ($model): ?>
        <h3><?= $model->name.' ('.$model->getReference().')' ?></h3>
        
        <h4>Activities:</h4>
        <?php
            $dataProvider = new ArrayDataProvider([
                'allModels' => $model->participations,
                'pagination' => false,
            ]);
            echo GridView::widget([
                'dataProvider' => $dataProvider,
                'layout' => '{items}',
                'tableOptions' => ['class' => 'table table-hover table-striped'],
                'columns' => [
                    [
                        'attribute' => 'activity.datetime',
                        'label' => 'Date',
                        'format' => 'datetime',
                        'value' => function ($data) {
                            return $data->activity->datetime;
                        },
                    ],
                    [
                        'attribute' => 'activity.title',
                        'label' => 'Title',
                        'value' => function ($data) {
                            return $data->activity->title;
                        },
                    ],
                    [
                        'label' => 'Registered',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::checkbox('registered', $data->registered, [
                                'disabled' => true,
                            ]);
                        },
                        'contentOptions' => ['style' => 'text-align:center'],
                    ],
                ],
            ]);
        ?>
    <?php else: ?>
        <div class="alert alert-danger">
            Booking not found.
        </div>
    <?php endif; ?>
</div>