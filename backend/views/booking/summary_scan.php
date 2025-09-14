<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\data\ArrayDataProvider;

?>

<div class="booking-summary">
    <?php if ($model): ?>
        <h3><?= $model->name.' ('.Html::a($model->getReference(), ['/booking/view', 'uuid' => $model->uuid]).')' ?></h3>
        
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
                        'contentOptions' => ['class' => 'hidden-sm hidden-xs'],
                        'headerOptions' => ['class' => 'hidden-sm hidden-xs'],
                    ],
                    [
                        'attribute' => 'activity.title',
                        'label' => 'Title',
                        'format' => 'raw',
                        'value' => function ($data) {
                            return Html::a($data->activity->title, ['/activity/view', 'uuid' => $data->activity->uuid]);;
                        },
                    ],
                    [
                        'attribute' => 'quantity',
                        'label' => 'Quantity',
                        'value' => function ($data) {
                            return (($data->quantity > 1)?$data->times_registered.'/'.$data->quantity.' ':$data->quantity);
                        },
                        'headerOptions' => ['class' => 'hidden-sm hidden-xs'],
                    ],
                    [
                        'label' => 'Registered',
                        'format' => 'raw',
                        'value' => function ($data) {
                            $icon = $data->registered ? 'check' : 'unchecked';
                            $class = $data->registered ? 'text-success' : 'text-muted';
                            
                            return '<span class="glyphicon glyphicon-'.$icon.' '.$class.'" aria-hidden="true"></span>';
                        },
                        'contentOptions' => ['style' => 'text-align:center'],
                        'headerOptions' => ['class' => 'hidden-sm hidden-xs'],
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