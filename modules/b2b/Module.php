<?php
// Модуль для сторонних корпоративных сотрудников и руководителей

namespace app\modules\b2b;

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

class Module extends BaseModule
{
    public $bodyClass = null;
    public $controllerNamespace = 'app\modules\b2b\controllers';
    public $defaultRoute = 'site';
    public $layout = '@app/modules/common/layouts/main';
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'actions'=>['signup'],
                        'allow'=>true,
                        'roles'=>['?']
                    ],
                    [
                        'allow'=>true,
                        'roles'=>['@']
                    ]
                ]
            ]
        ];
    }

    public function init()
    {
        parent::init();
        
        Yii::$app->homeUrl = ['/b2b'];
    }
}