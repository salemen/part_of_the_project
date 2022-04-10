<?php
namespace app\modules\payment\actions\yakassa;

use Yii;
use yii\base\Action;
use yii\web\Response;

class BaseAction extends Action
{
    public $actionName;
    public $beforeResponse;
    public $component = 'yakassa';    
    
    public function init()
    {
        parent::init();
        $this->controller->enableCsrfValidation = false;
        Yii::$app->response->setStatusCode(200);
        Yii::$app->response->format = Response::FORMAT_RAW;
        Yii::$app->response->headers->set('Content-Type', 'application/xml; charset=utf-8');
    }
    
    public function run()
    {
        $request = Yii::$app->request->post();
        
        if (!$this->checkMD5($request)) {
            return $this->buildResponse($this->actionName, $request['invoiceId'], 1, 'Ошибка авторизации');
        }
        
        if (!$this->beforeResponse) {
            return $this->buildResponse($this->actionName, $request['invoiceId'], 0, 'Успешно');
        }
        
        if (call_user_func($this->beforeResponse, Yii::$app->request)) {
            return $this->buildResponse($this->actionName, $request['invoiceId'], 0, 'Успешно');
        } else {
            return $this->buildResponse($this->actionName, $request['invoiceId'], 100, 'Отказ в приеме перевода');
        }
    }  
    
    public function buildResponse($action, $invoiceId, $resultCode, $message = null)
    {
        $xml = new \DOMDocument("1.0", "utf-8");
        $child = $xml->createElement($action . "Response");
        $child->setAttribute('performedDatetime', date("Y-m-d\TH:i:s.000P"));
        $child->setAttribute('code', $resultCode);
        $child->setAttribute('invoiceId', $invoiceId);
        $child->setAttribute('shopId', $this->getComponent()->shopId);
        if ($message) {
            $child->setAttribute('message', $message);
        }        
        $xml->appendChild($child);
        return $xml->saveXML();
    }
    
    public function getComponent()
    {
        return Yii::$app->get($this->component);
    }
    
    public function checkMD5($request)
    {
        $str = implode(';', [
            $request['action'],
            $request['orderSumAmount'],
            $request['orderSumCurrencyPaycash'],
            $request['orderSumBankPaycash'],
            $request['shopId'],
            $request['invoiceId'],
            $request['customerNumber'],
            $this->getComponent()->shopPassword
        ]);
        
        $md5 = strtoupper(md5($str));
        
        if ($md5 != strtoupper($request['md5'])) {
            return false;
        } else {
            return true;
        }
    }
}