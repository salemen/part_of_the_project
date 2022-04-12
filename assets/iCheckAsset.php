<?php
// Подключение шаблона iCheck (красивые checkbox & radio)

namespace app\assets;

use yii\web\AssetBundle;

class iCheckAsset extends AssetBundle
{
    public $sourcePath = '@vendor/npm-asset/icheck';
    public $css = [
        'skins/all.css'
    ];
    public $js = [
        'icheck.min.js'
    ];
    public $depends = [];
}