<?php
namespace app\modules\payment\actions;

use Yii;
use yii\base\Action;
use app\models\payments\Payments;

class PaymentSuccess extends Action
{
    public function run()
    {
        Yii::$app->session->setFlash('paymentSuccess', [
            'title'=>'Спасибо!',
            'content'=>'Ваш платеж успешно принят.',
            'type'=>'green'
        ]);

        $type = Yii::$app->request->get('serviceType');

        if ($type) {
            switch ($type) {
                case Payments::TYPE_CARDIOGRAM:
                    return $this->redirect('/cardio');
                case Payments::TYPE_CONSULT:
                    return $this->redirect('/consult');                
            }
        }

        return $this->redirect(Yii::$app->homeUrl);
    }
    
    protected function redirect($url)
    {
        return Yii::$app->response->redirect($url);
    }        
}