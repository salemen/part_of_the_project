<?php
// Модуль платежей

namespace app\modules\payment;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $bodyClass = null;
    public $controllerNamespace = 'app\modules\payment\controllers';
}