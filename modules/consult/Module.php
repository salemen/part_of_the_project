<?php
// Модуль проведения консультаций

namespace app\modules\consult;

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

class Module extends BaseModule
{
    public $bodyClass = null;
    public $controllerNamespace = 'app\modules\consult\controllers';
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
                        'roles'=>['@']
                    ]
                ],
                'denyCallback'=>function($rule, $action) {
                    Yii::$app->user->loginUrl = array_merge(Yii::$app->user->loginUrl, ['redirect'=>Yii::$app->request->url]);
                    Yii::$app->response->redirect(Yii::$app->user->loginUrl);         
                }
            ]
        ];
    }
    
    public function init()
    {
        parent::init();
        
        Yii::$app->homeUrl = ['/consult'];
    }
}