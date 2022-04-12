<?php
namespace app\widgets\speaker;

use yii\web\AssetBundle;

class Asset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/assets';

    public $js = [
        'js/main.js'
    ];

    public $depends = [
        'yii\web\JqueryAsset',
        'app\assets\FontAwesomeAsset'
    ];
}