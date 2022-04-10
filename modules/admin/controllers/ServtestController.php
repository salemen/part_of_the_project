<?php
namespace app\modules\admin\controllers;

use Exception;
use Yii;
use yii\base\DynamicModel;
use yii\filters\AccessControl;
use yii\web\Controller;

class ServtestController extends Controller
{      
    public function behaviors()
    {
        return [
            'access'=>[
                'class'=>AccessControl::className(),
                'rules'=>[
                    [
                        'allow'=>true,                        
                        'roles'=>['admin']
                    ]
                ],
                'denyCallback'=>function($rule, $action) {
                    Yii::$app->user->loginUrl = array_merge(Yii::$app->user->loginUrl, ['redirect'=>Yii::$app->request->url]);
                    Yii::$app->response->redirect(Yii::$app->user->loginUrl);
                }
            ]
        ];
    }
    
    public function actionIndex()
    {        
        $emailModel = $this->initEmail();
        $smsModel = $this->initSms();
                
        return $this->render('index', [
            'emailModel'=>$emailModel,
            'smsModel'=>$smsModel
        ]);
    }
    
    protected function initEmail()
    {
        $model = new DynamicModel(['email']);
        $model->addRule(['email'], 'required')->addRule(['email'], 'email');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {            
            if (Yii::$app->email->send($model->email, 'Test subject', 'Test message', false)) {
                $this->showAlert('На указанный E-mail отправлено тестовое сообщение.');
            } else {
                $this->showAlert('Сообщение не было отправлено.', 'red');
            }
            
            $model->email = '';
        }
        
        return $model;
    } 
    
    protected function initSms()
    {
        $model = new DynamicModel(['phone']);
        $model->addRule(['phone'], 'required');
        
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {           
            try {
                Yii::$app->sms->send($model->phone, 'Test message');
                $this->showAlert('На указанный номер телефона отправлено тестовое сообщение.');
            } catch (Exception $e) {
                $this->showAlert($e->getMessage(), 'red');
            } finally {
                $model->phone = '';
            }
        }
        
        return $model;
    }      

    protected function showAlert($content, $type = 'green')
    {
        Yii::$app->session->setFlash('alert', [
            'title'=>'Внимание!',
            'content'=>$content,
            'type'=>$type
        ]);
    }        
}