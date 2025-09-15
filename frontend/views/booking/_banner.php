<header class="site-header" style="background-image: url(<?= \Yii::getAlias('@web') ?>/uploads/<?= $event->banner ?>);">
    <a class="home-link" href="<?= $event->website ?>" title="<?= $event->title ?>" rel="home">
        <h1 class="site-title"><?= $event->title ?></h1>
        <h2 class="site-description">Brussels, Belgium</h2>
    </a>
</header>
<div class="title">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1><?= $event->title ?> - <?= Yii::t('booking', 'Book your activities')?></h1>
            </div>
            <div class="col-md-2"><a class="btn btn-default" href="<?= $event->website ?>"><?= Yii::t('booking', 'Back to the website') ?></a></div>
        </div>
    </div>
</div>