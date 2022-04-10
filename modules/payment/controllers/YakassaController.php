<?php
namespace app\modules\payment\controllers;

use Yii;
use yii\filters\VerbFilter;
use app\models\payments\Payments;
use app\models\payments\PaymentsOnline;
use app\modules\payment\actions\yakassa\CheckOrder;
use app\modules\payment\actions\yakassa\PaymentAviso;

class YakassaController extends DefaultController
{
    public function beforeAction($action)
    {
        if ($action->id === 'check-order' || $action->id === 'payment-aviso') {
            $this->enableCsrfValidation = false;
        }

        return parent::beforeAction($action);
    }
    
    public function behaviors()
    {
        return [
            'verbs'=>[
                'class'=>VerbFilter::className(),
                'actions'=>[
                    'check-order'=>['post'],
                    'payment-aviso'=>['post']
                ]
            ]
        ];
    } 
    
    public function actions()
    {
        return [
            'check-order'=>[
                'class'=>CheckOrder::className()
            ],
            'payment-aviso'=>[
                'class'=>PaymentAviso::className(),
                'beforeResponse'=>function ($request) {
                    return $this->processRequest($request);
                }
            ]
        ];
    }

    protected function processRequest($request)
    {
        $type = $request->post('serviceType');
        
        if ($type === 'payments-online') {
            return $this->saveOnlinePayments($request);
        } else {
            return $this->savePayments($request);
        }
    }
    
    protected function savePayments($request)
    {
        $exist = Payments::findOne(['invoiceId'=>$request->post('invoiceId')]);

        if (!$exist) {
            $model = new Payments();
            $model->invoiceId = $request->post('invoiceId');
            $model->customerNumber = $request->post('customerNumber');
            $model->orderStatus = Payments::STATUS_PAYD;
            $model->orderType = $request->post('serviceType');
            $model->orderNumber = $request->post('serviceNumber'); 
            $model->orderComment = $request->post('serviceComment') ? $request->post('serviceComment') : null;
            $model->orderSumAmount = $request->post('orderSumAmount');            
            $model->orderSumCurrencyPaycash = $request->post('orderSumCurrencyPaycash');
            $model->shopSumAmount = $request->post('shopSumAmount');
            $model->paymentType = $request->post('paymentType');

            if ($model->save()) {
                $this->saveNotification($model);
                $this->updateModel($model->orderNumber, $model->orderType);
                return true;
            } else {
                return false;
            }
        }

        return true;
    }        


    protected function saveOnlinePayments($request)
    {
        $data = $request->post();        
        $exist = PaymentsOnline::find()->where(['invoice_id'=>$data['invoiceId']])->exists();
        
        if (!$exist) {        
            $model = new PaymentsOnline([
                'invoice_id'=>$data['invoiceId'],
                'service_id'=>$data['serviceNumber'],
                'user_id'=>$data['customerNumber'],
                'pay_amount'=>$data['orderSumAmount'],
                'pay_result'=>$data['shopSumAmount'],
                'pay_paycash'=>$data['orderSumCurrencyPaycash'],
                'pay_type'=>$data['paymentType']
            ]);

            return $model->save();
        }

        return true;
    }        
}