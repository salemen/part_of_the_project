<?php
namespace app\modules\payment\controllers;

use yii\helpers\StringHelper;
use yii\web\Controller;
use app\models\cron\CronNotification;
use app\models\payments\Payments;
use app\modules\payment\actions\PaymentFail;
use app\modules\payment\actions\PaymentSuccess;
use app\modules\payment\actions\PaymentTest;

class DefaultController extends Controller
{    
    public function actions()
    {
        return [
            'payment-fail'=>[
                'class'=>PaymentFail::className()
            ],
            'payment-success'=>[
                'class'=>PaymentSuccess::className()
            ],
            'payment-test'=>[
                'class'=>PaymentTest::className()
            ]
        ];
    }      
    
    protected function findModel($className, $id)
    {
        if (($model = $className::findOne($id)) !== null) {
            return $model;
        }
        
        $baseName = StringHelper::basename($className);
        
        throw new NotFoundHttpException("Экземпляр модели {$baseName} не найден.");
    }
    
    protected function saveNotification($model)
    {
        switch ($model->orderType) {
            case Payments::TYPE_CARDIOGRAM:
                $target = $model->orderNumber;
                $type = CronNotification::TYPE_NEW_CARDIO;
                break;
            case Payments::TYPE_CONSULT:
                $target = $model->orderNumber;
                $type = CronNotification::TYPE_NEW_CONSULT;
                break;            
            default:
                return true;
        }
        
        (new CronNotification([
            'message'=>null,
            'target'=>(string)$target,
            'type'=>$type
        ]))->save();
    }
    
    protected function updateModel($id, $type)
    {
        switch ($type) {
            case Payments::TYPE_CARDIOGRAM:
                $className = '\app\models\cardio\Cardio';
                break;
            case Payments::TYPE_CONSULT:
                $className = '\app\models\consult\Consult';
                break;
            default:
                return true;
        }
        
        $model = $this->findModel($className, $id);
        $model->updateAttributes(['is_payd'=>true]);
    }
}