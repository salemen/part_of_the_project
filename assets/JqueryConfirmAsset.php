<?php
// Подключение шаблона JqueryConfirm (красивые модальные окна, alert'ы и confirm'ы)

namespace app\assets;

use yii\web\AssetBundle;

class JqueryConfirmAsset extends AssetBundle
{
    public $sourcePath = '@vendor/bower-asset/jquery-confirm2/dist';
    public $css = [
        'jquery-confirm.min.css'
    ];
    public $js = [
        'jquery-confirm.min.js'
    ];
    public $depends = [];
}