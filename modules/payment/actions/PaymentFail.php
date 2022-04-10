<?php
namespace app\modules\payment\actions;

use Yii;
use yii\base\Action;

class PaymentFail extends Action
{
    public function run()
    {
        Yii::$app->session->setFlash('paymentSuccess', [
            'title'=>'Внимание!',
            'content'=>'Ваш платеж не выполнен.',
            'type'=>'red'
        ]);

        return $this->redirect(Yii::$app->homeUrl);
    }
    
    protected function redirect($url)
    {
        return Yii::$app->response->redirect($url);
    }        
}