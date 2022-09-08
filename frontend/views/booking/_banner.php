<header class="site-header" style="background-image: url(<?= \Yii::getAlias('@web') ?>/uploads/<?= $event->banner ?>);">
    <a class="home-link" href="http://brusselstangofestival.com/" title="The Brussels Tango Festival" rel="home">
        <h1 class="site-title">The Brussels Tango Festival</h1>
        <h2 class="site-description">Brussels, Belgium</h2>
    </a>
</header>
<div class="title">
    <div class="container">
        <div class="row">
            <div class="col-md-10">
                <h1><?= $event->title ?> - <?= Yii::t('booking', 'Book your activities')?></h1>
            </div>
            <div class="col-md-2"><a class="btn btn-default" href="http://www.brusselstangofestival.com"><?= Yii::t('booking', 'Back to the website') ?></a></div>
        </div>
    </div>
</div>