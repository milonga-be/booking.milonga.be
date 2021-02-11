<?php
$this->title = Yii::t('booking', 'Create Event');
?>
<h1><?= $this->title ?></h1>
<?= $this->render('_form', ['model' => $model])?>