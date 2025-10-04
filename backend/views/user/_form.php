<?php

use yii\helpers\Html;
use common\models\User;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?php
        $passwordHint = $model->isNewRecord ? '' : 'Leave blank if you don\'t want to change the password.';
    ?>
    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true])->hint($passwordHint) ?>

    <?= $form->field($model, 'role')->dropDownList(User::getRoles()) ?>

    <p class="text-right buttons">
        <a href="<?= Url::to(['user/index']) ?>" class="btn btn-default btn-md"><?= Yii::t('booking', 'Cancel')?></a>
        <button class="btn btn-primary btn-md"><?= $model->isNewRecord ? Yii::t('booking', 'Create') : Yii::t('booking', 'Update')?></button>
    </p>

    <?php ActiveForm::end(); ?>

</div>