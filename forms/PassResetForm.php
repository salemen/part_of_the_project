<?php
// Форма сброса пароля (через СМС)

namespace app\forms;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use app\models\employee\Employee;
use app\models\patient\Patient;

class PassResetForm extends Model
{
    public $phone;

    public function rules()
    {
        return [
            [['phone'], 'required'],
            [['phone'], PhoneInputValidator::className()],
            [['phone'], 'validatePhone']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'phone'=>'Номер телефона'
        ];
    }

    public function sendSms()
    {
        if ($this->validate()) {
            $password = sprintf("%06d", rand(1, 999999));
            $user = $this->getUser();
            $user->setPassword($password);
            if ($user->save()) {
                Yii::$app->sms->send($this->phone, $password . ' - Ваш новый пароль для входа в Личный Кабинет Онлайн поликлиники 0323');
                return true;
            }
        } else {
            return false;
        }
    }
    
    public function validatePhone($attribute)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user === null) {
                $this->addError($attribute, 'Пользователь не найден.');
            }
            if ($user instanceof Employee) {
                $this->addError($attribute, 'Восстановление пароля учетной записи сотрудника запрещено.');
            }
        }
    } 
    
    protected function getUser()
    {
        $model = null;
        
        if ($model === null) {
            $model = Employee::findByPhone($this->phone);             
        }
        
        if ($model === null) {
            $model = Patient::findByPhone($this->phone);       
        }
        
        return $model;
    }
}