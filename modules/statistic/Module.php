<?php
// Отображение стратистики
// TODO Можно вообще убрать, т.к. статистика для руководителей отображаентся в b2b модуле

namespace app\modules\statistic;

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\statistic\controllers';
    public $defaultRoute = 'site';
    
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>Yii::$app->session->has('employee_santal')
                    ]
                ]
            ]
        ];
    }
}