<?php
namespace app\modules\payment\actions;

use Yii;
use yii\base\Action;
use app\models\payments\Payments;

class PaymentTest extends Action
{
    public function run()
    {
        $ym_merchant_receipt = ['customerContact'=>'+79998887766', 'items'=>[['quantity'=>1, 
        'price'=>['amount'=>'1.00'], 'tax'=>1, 'text'=>'Онлайн консультация']]];

        $ymmerchantreceipt = json_encode($ym_merchant_receipt, JSON_UNESCAPED_UNICODE);
        
        $params = [
            'customerNumber'=>'Test customer',   
            'serviceType'=>Payments::TYPE_TEST,
            'serviceNumber'=>1,
            'serviceComment'=>'Test payment',
            'sum'=>'1.00',
            'ym_merchant_receipt'=>$ymmerchantreceipt              
        ];
        
        return Yii::$app->yakassa->payment($params);
    }      
}