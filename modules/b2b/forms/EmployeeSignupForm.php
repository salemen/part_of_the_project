<?php
namespace app\modules\b2b\forms;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use app\models\cron\CronNotification;
use app\models\employee\Employee;
use app\models\employee\EmployeePosition;
use app\models\employee\EmployeeRoles;
use app\models\patient\Patient;

class EmployeeSignupForm extends Model
{   
    public $user_f;
    public $user_i;
    public $user_o;
    public $city;
    public $user_birth;
    public $sex;    
    public $email;
    public $phone;
    public $org_id;
    public $empl_pos;
    public $empl_dep;
    
    private $password;
    
    public function attributeLabels()
    {
        return [
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'user_birth'=>'Дата рождения',
            'city'=>'Город',
            'sex'=>'Пол',
            'email'=>'Email',
            'phone'=>'Номер телефона',
            'org_id'=>'Организация',
            'empl_pos'=>'Должность',
            'empl_dep'=>'Подразделение'
        ];
    }

    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_o', 'user_birth', 'city', 'sex', 'email', 'phone', 'org_id', 'empl_pos', 'empl_dep'], 'required'],
            [['user_f', 'user_i', 'user_o', 'user_birth', 'city', 'phone', 'empl_pos', 'empl_dep'], 'string'],
            [['sex', 'org_id'], 'integer'],
            [['email'], 'email', 'message'=>'E-mail введен неверно.'],            
            [['email'], 'validateEmail'],
            [['phone'], PhoneInputValidator::className()],
            [['phone'], 'validatePhone']           
        ];
    }
    
    public function signup()
    {
        if ($this->validate()) {
            $transaction = Yii::$app->db->beginTransaction();
            $this->password = sprintf("%06d", rand(1, 999999));            
            
            $user = $this->saveUser();
            if ($user == null){
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении пользователя.');
            }            
            
            if (!$this->savePosition($user->id)) {
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении организации пользователя.');                
            } 
            
            if (!$this->saveNotification($user)) {
                $transaction->rollBack();                
                throw new ServerErrorHttpException('Ошибка при сохранении данных сообщения.');                
            }
            
            $transaction->commit();
            $this->updateUserAttributes($user);

            return true;
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
    
    protected function savePosition($user_id)
    {
        $model = new EmployeePosition([
            'id'=>Yii::$app->security->generateRandomString(16),
            'employee_id'=>$user_id,
            'empl_pos'=>$this->empl_pos,            
            'empl_dep'=>$this->empl_dep,
            'type'=>'Основное место работы',
            'org_id'=>$this->org_id,
            'is_doctor'=>1,
            'is_santal'=>0
        ]);
        
        return $model->save();
    }
    
    protected function saveUser()
    {
        //$count = Employee::find()->joinWith(['roles'])->where(['is_santal'=>0])->count();
        $model = new Employee([
            'id'=>Yii::$app->security->generateRandomString(32),
            'fullname'=>$this->setFullname(),
            'username'=>null,
            'user_birth'=>$this->user_birth,
            'city'=>$this->city,
            'email'=>trim($this->email),
            'phone'=>$this->phone,
            'sex'=>preg_match('/(ович|евич|ич)$/', $this->user_o)       
        ]);
        
        if ($model->save() && $model->setAuth($model->id, $this->password)) {
            return $model;
        }
        // die(var_dump($model->getErrors()));
        return null;
    }
    
    protected function updateUserAttributes($user)
    {
        $model = EmployeeRoles::findOne(['employee_id'=>$user->id]);
        
        if ($model) {
            $model->updateAttributes(['is_santal'=>0]);
        }
    }
    
    protected function setFullname()
    {
        return mb_convert_case(trim($this->user_f) . ' ' . trim($this->user_i) . ' ' . trim($this->user_o), MB_CASE_TITLE, 'utf-8');
    }
}