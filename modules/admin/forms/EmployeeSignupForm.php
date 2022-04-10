<?php
// Форма добавления временных сотруднкив или сотрудников не работающих официально

namespace app\modules\admin\forms;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use app\models\employee\Employee;
use app\models\patient\Patient;

class EmployeeSignupForm extends Model
{   
    public $user_f;
    public $user_i;
    public $user_o;
    public $city;   
    public $email;
    public $phone;
    public $password;
    
    public function attributeLabels()
    {
        return [
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'city'=>'Город',
            'email'=>'Email',
            'phone'=>'Номер телефона',
            'password'=>'Пароль'
        ];
    }

    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_o', 'city', 'password'], 'required'],
            [['user_f', 'user_i', 'user_o', 'city', 'phone', 'email'], 'string'],
            [['email'], 'email', 'message'=>'E-mail введен неверно.'],            
            [['email'], 'validateEmail'],
            [['phone'], PhoneInputValidator::className()],
            [['phone'], 'validatePhone'],
            [['password'], 'string', 'min'=>6]
        ];
    }
    
    public function signup()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();          
            
            $user = $this->saveUser();
            if ($user == null){
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении пользователя.');
            }
            
            $transaction->commit();  

            return true;
        }
        
        return false;
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
    
    protected function saveUser()
    {
        $count = Employee::find()->where(['is_official'=>0])->count();
        $model = new Employee([
            'id'=>Yii::$app->security->generateRandomString(32),
            'fullname'=>$this->setFullname(),
            'username'=>'temp' . sprintf("%06d", $count + 1),
            'city'=>$this->city,
            'email'=>($this->email !== '') ? trim($this->email) : null,
            'phone'=>($this->phone !== '') ? $this->phone : null,
            'sex'=>preg_match('/(ович|евич|ич)$/', $this->user_o)
        ]);
        
        if ($model->save() && $model->setAuth($model->id, $this->password)) {
            return $model;
        }
        
        return null;
    }
    
    protected function setFullname()
    {
        return mb_convert_case(trim($this->user_f) . ' ' . trim($this->user_i) . ' ' . trim($this->user_o), MB_CASE_TITLE, 'utf-8');
    }
}