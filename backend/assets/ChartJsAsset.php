<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class ChartJsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower/chartjs/';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'dist/Chart.min.js',
    ];
    public $depends = [
    ];
}
