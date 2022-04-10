<?php
// Ситуационный центр COVID-19

namespace app\modules\covid;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $bodyClass = null;
    public $controllerNamespace = 'app\modules\covid\controllers';
    public $defaultRoute = 'site';
    public $layout = '@app/modules/covid/views/layouts/main';

    public function init()
    {
        parent::init();
        
        Yii::$app->homeUrl = ['/covid'];
    }
}