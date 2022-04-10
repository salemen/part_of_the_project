<?php
// Авторизация через authKey

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\employee\Employee;
use app\models\patient\Patient;

class AuthForm extends Model
{
    public $auth_key;
    public $redirect = null;
    public $rememberMe = false;

    public function rules()
    {
        return [
            [['auth_key'], 'required']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'auth_key'=>'Ключ авторизации'
        ];
    }
    
    public function login()
    {
        if ($this->validate() && $user = $this->getUser()) {            
            if ($user instanceof Employee) {
                Yii::$app->session->set('employee_santal', true);
            }
            
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }   
    
    protected function getUser()
    {
        $auth_key = $this->auth_key;
        $model = Employee::findOne(['auth_key'=>$auth_key, 'status'=>Employee::STATUS_ACTIVE]);
        
        if ($model === null) {
            $model = Patient::findOne(['auth_key'=>$auth_key, 'status'=>Patient::STATUS_ACTIVE]);
        }
        
        if ($model !== null) {
            $model->updateAttributes([
                'auth_key'=>Yii::$app->security->generateRandomString()
            ]);
        }
        
        return $model;
    }
}