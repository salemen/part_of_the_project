<?php
// Форма регистрации пациента

namespace app\forms;

use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use app\models\employee\Employee;
use app\models\patient\Patient;
use yii\captcha\Captcha;

class SignupForm extends Model
{
    public $email;
    public $phone;
    public $password;
    public $captcha;

    public function rules()
    {
        return [
            [['phone', 'password'], 'required'],
            [['phone'], 'string', 'max'=>255],
            [['phone'], PhoneInputValidator::className()],
            [['phone'], 'validatePhone'],
            [['email'], 'email'],
            [['email'], 'validateEmail'],
            [['password'], 'string', 'min'=>6],
            ['captcha', 'captcha', 'skipOnEmpty' =>  !Captcha::checkRequirements(), 'captchaAction' => '/site/captcha']

        ];
    }

    public function attributeLabels()
    {
        return [
            'email'=>'E-mail',
            'phone'=>'Номер телефона',
            'password'=>'Пароль',

        ];
    }

    public function signup()
    {
        if (!$this->validate()) { return null; }
        
        $user = new Patient();
        $user->generateAuthKey();
        $user->generateId();
        $user->fullname = 'Аноним';
        $user->phone = $this->phone;
        $user->email = ($this->email && $this->email !== '') ? trim($this->email) : null;
        $user->setPassword($this->password);        

        return $user->save() ? $user : null;
    }
    
    public function validateEmail($attribute)
    {        
        if ($this->email) {            
            if (!$this->hasErrors()) {
                $exists = false;
                
                if ($exists === false) {
                    $exists = Employee::find()->where(['email'=>$this->email])->exists();
                }
                
                if ($exists === false) {
                    $exists = Patient::find()->where(['email'=>$this->email])->exists();
                }
                        
                if ($exists) {
                    $this->addError($attribute, 'Пользователь с таким E-mail уже зарегистрирован.');
                }
            }
        }
    }
    
    public function validatePhone($attribute)
    {        
        if ($this->phone) {            
            if (!$this->hasErrors()) {                   
                $exists = false;
                
                if ($exists === false) {
                    $exists = Employee::find()->where(['phone'=>$this->phone])->exists();
                }
                
                if ($exists === false) {
                    $exists = Patient::find()->where(['phone'=>$this->phone])->exists();
                }
                        
                if ($exists) {
                    $this->addError($attribute, 'Пользователь с таким номером телефона уже зарегистрирован.');
                }
            }
        }
    }
}