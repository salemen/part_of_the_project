<?php
// Подключение шаблона JqueryEasyzoom (зум изображений при наведении)

namespace app\assets;

use yii\web\AssetBundle;

class JqueryEasyzoomAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/easyzoom';
    public $css = [
        'css/easyzoom.css'
    ];
    public $js = [
        'dist/easyzoom.js'
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}