<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'lower_limit')?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'higher_limit')?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'activity_group_id')->dropDownlist($event->getActivityGroupsList())?>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'value')?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'type')->dropDownlist($model->getTypesList())?>
    </div>
</div>