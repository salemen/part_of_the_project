<?php
// Форма заявки вызова врача на дом

namespace app\forms\proposal;

use Yii;
use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\CommonUser;
use app\models\employee\Employee;
use app\models\user\UserData;
use app\models\user\UserProposal;
use app\models\data\Department;

class CallDoctorForm extends Model
{
    public $user_f;
    public $user_i;
    public $user_o;
    public $user_birth;
    public $sex;
    public $email;
    public $phone;    
    public $city;
    public $address;
    public $param1;
    public $comment;
    public $clinic;
    public $polis_exists = false;
    public $polis_oms_org;
    public $polis_oms_number;
    
    public function init()
    {
        parent::init();

        $user = Yii::$app->user;
        
        if (!$user->isGuest) {
            $user = $user->identity;
            $fullname = explode(' ', $user->fullname);
            $this->user_f = $fullname[0];
            if(!empty($fullname[1])){
                $this->user_i = $fullname[1];
            }

            $this->user_o = isset($fullname[2]) ? $fullname[2] : '-';
            $this->user_birth = $user->user_birth;
            $this->sex = $user->sex;
            $this->email = $user->email;
            $this->phone = ($user instanceof Employee) ? ($user->phone) ? $user->phone : $user->phone_work : $user->phone;
            $this->city = $user->city;
            
            if ($user->data) {
                $data = $user->data;
                $this->address = $data->address;
                if ($data->clinic !== null){
                    $this->clinic = $data->clinic;
                }
                if ($data->polis_oms_org !== null || $data->polis_oms_number !== null) {
                    $this->polis_exists = true;
                    $this->polis_oms_org = $data->polis_oms_org;
                    $this->polis_oms_number = $data->polis_oms_number;

                }
            }
        }
    }
    
    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_birth', 'sex', 'email', 'phone', 'city', 'address', 'polis_exists', 'param1', 'comment'], 'required'],
            [['user_f', 'user_i', 'user_o', 'user_birth', 'email', 'phone', 'city', 'address', 'param1', 'polis_oms_org', 'polis_oms_number', 'clinic'], 'string', 'max'=>255],
            [['phone'], PhoneInputValidator::className()],
            [['sex', 'polis_exists'], 'integer'],
            [['email'], 'email'],
            [['comment'], 'string'],
            ['user_f', 'match','pattern'=> '/^[A-Za-zАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯабвгдеёжзийклмнопрстуфхцчшщьыъэюя\s,]+$/'],
            ['user_i', 'match','pattern'=> '/^[A-Za-zАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯабвгдеёжзийклмнопрстуфхцчшщьыъэюя\s,]+$/'],
            ['user_o', 'match','pattern'=> '/^[A-Za-zАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЬЫЪЭЮЯабвгдеёжзийклмнопрстуфхцчшщьыъэюя\s,]+$/'],
            ['polis_oms_number', 'match','pattern'=> '/^[1234567890\s,]+$/']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'user_birth'=>'Дата рождения',
            'sex'=>'Пол',
            'email'=>'E-mail',
            'phone'=>'Номер телефона',            
            'city'=>'Город',
            'address'=>'Адрес',
            'param1'=>'Дата и время желаемого визита врача',
            'comment'=>'Жалобы',
            'clinic'=>'Клиника',
            'polis_exists'=>'',
            'polis_oms_org'=>'Полис ОМС (организация)',
            'polis_oms_number'=>'Полис ОМС (номер)'
        ];
    }
    
    public function save()
    {
        if (!$this->validate()) { return false; }
        
        $params = ArrayHelper::toArray($this);
        $params['fullname'] = implode(' ', [$this->user_f, $this->user_i, $this->user_o]);
        $user = CommonUser::getUser($params);
        
        if ($user === null) {
            $user = CommonUser::createUser($params);
        }
        
        if ($user) {
            Yii::$app->getUser()->login($user);
            $user_id = $user->id;
            $this->updateUserData($user_id);
            
            if (UserProposal::find()->where(['status'=>5, 'type_id'=>UserProposal::TYPE_CALL_DOCTOR, 'user_id'=>$user_id])->exists()) { return true; }
            
            $model = new UserProposal();            
            $model->type_id = UserProposal::TYPE_CALL_DOCTOR;
            $model->user_id = $user_id;
            $model->param1 = $this->param1;
            $model->comment = $this->comment;


            if ($model->save()) {

                

                return true;
            }
        }
        
        return false;
    }


    protected function updateUserData($user_id)
    {
        $model = UserData::findOne(['user_id' => $user_id]) ?: new UserData(['user_id' => $user_id]);

        if (!$model->address) {
            $model->address = $this->address;
        }
        if ($this->polis_exists) {
            if (!$model->polis_oms_org) {
                $model->polis_oms_org = $this->polis_oms_org;
            }
            if (!$model->polis_oms_number) {
                $model->polis_oms_number = $this->polis_oms_number;
            }
        }
        if (!$model->clinic) {
            $dep = Department::find()->select('name')->where(['id' => $this->clinic])->all();
            if (!empty($dep[0]['name'])) {
                $model->clinic = $dep[0]['name'];
            }
        }elseif ($model->clinic) {
            $dep = Department::find()->select('name')->where(['id' => $model->clinic])->all();
            if (!empty($dep[0]['name'])) {
                $model->clinic = $dep[0]['name'];
            }
        }
            $model->save();
    }
}