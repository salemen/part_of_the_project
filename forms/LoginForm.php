<?php
// Форма авторизации

namespace app\forms;

use Yii;
use yii\base\Model;
use app\helpers\AppHelper;
use app\models\employee\Employee;
use app\models\patient\Patient;

class LoginForm extends Model
{
    public $identity;
    public $password;
    public $rememberMe = false;

    public function rules()
    {
        return [
            [['identity', 'password'], 'required'],
            [['identity'], 'string'],
            [['rememberMe'], 'boolean'],
            [['identity'], 'validateIdentity'],
            [['password'], 'validatePassword']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'identity'=>'Логин / E-mail / Номер телефона / СНИЛС',
            'password'=>'Пароль',
            'rememberMe'=>'Запомнить меня'
        ];
    }
    
    public function login()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            if ($user instanceof Employee) {
                Yii::$app->session->set('employee_santal', true);
            }
            return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    public function validateIdentity($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user === null) {
                $this->addError($attribute, 'Пользователь не найден.');
            }
        }
    } 

    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user === null || !$user->validatePassword($this->password)) {
                $message = 'Пароль введен неверно.';
                if ($user instanceof Employee && $user->roles->is_santal) {
                    $message .= '<br>Вы являетесь сотрудником. Используйте корпоративные данные для входа.';
                }     
                $this->addError($attribute, $message);
            }
        }
    }      
    
    protected function getUser()
    {
        $model = null;
        
        if ($model === null) {
            $model = Employee::findByEmail($this->identity);
            if ($model === null) {
                $model = Employee::findByUsername($this->identity);      
                if ($model === null) {
                    $model = Employee::findBySnils($this->identity);                    
                    if ($model === null) {
                        $model = Employee::findByPhone(AppHelper::normalizePhone($this->identity));
                    }
                }
            }
        }
        
        if ($model === null) {
            $model = Patient::findByEmail($this->identity);
            if ($model === null) {
                $model = Patient::findByUsername($this->identity);  
                if ($model === null) {
                    $model = Patient::findBySnils($this->identity);                    
                    if ($model === null) {
                        $model = Patient::findByPhone(AppHelper::normalizePhone($this->identity));
                    }
                }
            }
        }
        
        return $model;
    }
}