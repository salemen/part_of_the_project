<?php
// Подключение некоторых отдельных скриптов из AdminLTE

namespace app\assets;

use yii\web\AssetBundle;

class AdminLTEBowerAsset extends AssetBundle
{
    public $sourcePath = '@vendor/almasaeed2010/adminlte/bower_components';
    public $css = [];
    public $js = [
        'jquery-slimscroll/jquery.slimscroll.min.js'
    ];
    public $depends = [];
}