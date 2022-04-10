<?php
// REST Api для сторонник сервисов (например 1с)

namespace app\modules\api;

use Yii;
use yii\base\Module as BaseModule;

class Module extends BaseModule
{
    public $bodyClass = null;
    public $controllerNamespace = 'app\modules\api\controllers';

    public function init()
    {
        parent::init();
        
        Yii::$app->user->enableSession = false;
        Yii::$app->user->identityClass = 'app\modules\api\models\User';
    }
}