<?php
// Модуль панели администратора

namespace app\modules\admin;

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

class Module extends BaseModule
{
    public $bodyClass = null;
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $defaultRoute = 'site';
    public $layout = '@app/modules/common/layouts/main';
        
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,                        
                        'roles'=>['permAdminPanel']
                    ]
                ]
            ]
        ];
    }

    public function init()
    {
        parent::init();
        
        Yii::$app->homeUrl = ['/admin'];
    }
}