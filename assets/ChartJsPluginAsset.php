<?php
// Подключение ChartJs (отобрыжение данных в виде графиков, "пончиков" и т.д.)

namespace app\assets;

use yii\web\AssetBundle;

class ChartJsPluginAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset';
    public $css = [
    ];
    public $js = [
        'chartjs-plugin-annotation/chartjs-plugin-annotation.min.js',
        'chartjs-plugin-datalabels/dist/chartjs-plugin-datalabels.min.js'
    ];
    public $depends = [
        'app\assets\ChartJsAsset'
    ];
}