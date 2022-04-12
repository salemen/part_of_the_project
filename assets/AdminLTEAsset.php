<?php
// Подключение шаблона AdminLTE (визуальная составляющая сайта + js скрипты)

namespace app\assets;

use yii\web\AssetBundle;

class AdminLTEAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/dist';
    public $css = [
        'css/AdminLTE.min.css',
        'css/skins/_all-skins.min.css'
    ];
    public $js = [
        'js/adminlte.min.js'
    ];
    public $depends = [];
}