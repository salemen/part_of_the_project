<?php
// Форма регистрации сторонней организации
// TODO Переделать в простую форму регистрации стороннего руководителя

namespace app\forms;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use app\models\cron\CronNotification;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\Employee;
use app\models\employee\EmployeePosition;
use app\models\patient\Patient;

class OrgSignupForm extends Model
{
    public $org_name;
    public $org_city;
    public $org_inn;
    public $org_kpp;
    public $org_ogrn;
    public $org_address;    
    public $user_f;
    public $user_i;
    public $user_o;
    public $email;
    public $phone;
    public $password;
    
    public function attributeLabels()
    {
        return [            
            'org_name'=>'Название организации',
            'org_city'=>'Город',
            'org_inn'=>'ИНН',
            'org_kpp'=>'КПП',
            'org_ogrn'=>'ОГРН',
            'org_address'=>'Юр. Адрес',
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'email'=>'Email',
            'phone'=>'Номер телефона',
            'password'=>'Пароль'
        ];
    }

    public function rules()
    {
        return [
            [['org_name', 'org_city', 'org_inn', 'org_kpp', 'org_ogrn', 'org_address', 'email', 'phone', 'password', 'user_f', 'user_i', 'user_o'], 'required'],
            [['org_name', 'org_city', 'org_inn', 'org_kpp', 'org_ogrn', 'org_address', 'user_f', 'user_i', 'user_o'], 'string'],
            [['org_inn'], 'unique', 'targetClass'=>Organization::className(), 'targetAttribute'=>['org_inn'=>'inn'], 'message'=>'Организация с таким ИНН уже существует.'],
            [['org_inn'], 'match', 'pattern'=>'/^\d{10}$/', 'message'=>'Значение «{attribute}» должно содержать 10 цифр.'],              
            [['org_kpp'], 'match', 'pattern'=>'/^\d{9}$/', 'message'=>'Значение «{attribute}» должно содержать 9 цифр.'],
            [['email'], 'email', 'message'=>'E-mail введен неверно.'],
            [['phone'], PhoneInputValidator::className()],
            [['email'], 'validateEmail'],
            [['phone'], 'validatePhone']
        ];
    }
    
    public function signup()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $org = $this->saveOrg();
            if ($org == null) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка при сохранении организации.');
            }
            
            if (!$this->saveDep($org)) {
                $transaction->rollBack();
                throw new ServerErrorHttpException('Ошибка при сохранении подразделения.');
            }
            
            $user = $this->saveUser();
            if ($user == null) {
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении пользователя.');
            }            
            
            if (!$this->savePosition($org, $user)) {
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении организации пользователя.');                
            }   
            
            if (!$this->saveNotification($user)) {
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении данных сообщения.');                
            }
            
            $transaction->commit();
            Yii::$app->session->set('employee_santal', true);

            return Yii::$app->user->login($user);
        }
        
        return false;
    }
    
    public function validateEmail($attribute)
    {            
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
    
    public function validatePhone($attribute)
    {           
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
    
    protected function saveDep($org)
    {
        $model = new Department([
            'name'=>'Основное',
            'address'=>$org->address,
            'short_address'=>$org->address,
            'alias'=>null,
            'org_id'=>$org->id,
            'is_santal'=>0
        ]);
        
        return $model->save();
    }
    
    protected function saveNotification($user)
    {
        $email = $user->email;
        $phone = $user->phone;
        $username = $user->username;        
        $password = $this->password;
        
        $message = "Для Вас была создана учетная запись в Онлайн-Поликлиникe Санталь.<br><br>"
            . "Для авторизации в системе, пожалуйста, используйте эти данные:<br>"
            . "Логин: <b>{$username}</b><br>"
            . "Номер телефона: <b>{$phone}</b><br>"
            . "Email: <b>{$email}</b><br>"
            . "Пароль: <b>{$password}</b><br>";
            
        $model = new CronNotification();
        $model->target = $user->id;
        $model->message = base64_encode($message);
        $model->type = CronNotification::TYPE_NEW_EMPLOYEE;
        
        return $model->save();
    }

    protected function saveOrg()
    {
        $model = new Organization([
            'name'=>$this->org_name,
            'city'=>$this->org_city,
            'inn'=>$this->org_inn,
            'kpp'=>$this->org_kpp,
            'ogrn'=>$this->org_ogrn,
            'address'=>$this->org_address,
            'is_santal'=>0
        ]);
        
        return $model->save() ? $model : null;
    }
    
    protected function savePosition($org, $user)
    {
        $model = new EmployeePosition([
            'id'=>Yii::$app->security->generateRandomString(16),
            'employee_id'=>$user->id,
            'empl_pos'=>'Представитель организации',            
            'empl_dep'=>'Основное',
            'type'=>'Основное место работы',
            'org_id'=>$org->id,
            'is_doctor'=>0,
            'is_santal'=>0
        ]);
        
        return $model->save();
    }
    
    protected function saveUser()
    {
        $count = Employee::find()->where(['is_santal'=>0])->count();
        $model = new Employee([
            'id'=>Yii::$app->security->generateRandomString(32),
            'fullname'=>$this->setFullname(),
            'username'=>'corp' . sprintf("%06d", $count + 1),
            'city'=>$this->org_city,
            'email'=>$this->email,
            'phone'=>$this->phone,
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