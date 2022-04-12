<?php
// Основные стили и скрипты

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'plugins/jquery.magnific-popup/jquery.magnific-popup.min.css',
        'css/fonts.css',
        'css/custom.css'
    ];
    public $js = [
        'plugins/jquery.magnific-popup/jquery.magnific-popup.min.js',
        'js/custom.js'
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapPluginAsset',
        'app\assets\FontAwesomeAsset',
        'app\assets\iCheckAsset',
        'app\assets\JqueryConfirmAsset',
        'app\assets\AdminLTEAsset',
    ];
}



