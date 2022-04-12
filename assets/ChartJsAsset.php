<?php
namespace app\assets;

use yii\web\AssetBundle;

class ChartJsAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset/chart.js/dist';
    public $css = [
        'Chart.min.css'
    ];
    public $js = [
        'Chart.min.js'
    ];
    public $depends = [];
}