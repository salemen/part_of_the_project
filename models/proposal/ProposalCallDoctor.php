<?php
namespace app\models\proposal;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\data\Department;
use app\models\employee\Employee;
use app\models\user\UserProposal;

class ProposalCallDoctor extends ActiveRecord
{
    public static function tableName()
    {
        return 'proposal_call_doctor';
    }

    public function init()
    {
        parent::init();

        if ($this->proposal) {
            $model = $this->proposal;
            $user = ($model->employee) ? $model->employee : $model->patient;
            $fullname = explode(' ', $user->fullname);
            $this->user_f = $fullname[0];
            if (!empty($fullname[1])){
                $this->user_i = $fullname[1];
            };
            $this->user_o = isset($fullname[2]) ? $fullname[2] : '-';
            $this->user_birth = $user->user_birth;
            $this->phone = ($user instanceof Employee) ? ($user->phone) ? $user->phone : $user->phone_work : $user->phone;
            $this->address = $user->city;
            $this->city = $user->city;
            $this->complaint = $model->comment;
            $visit_datetime = explode(' ', $model->param1);
            $this->visit_date = $visit_datetime[0];

        }
    }

    public function behaviors()
    {
        return [
            'blameable'=>[
                'class'=>BlameableBehavior::className()
            ],
            'timestamp'=>[
                'class'=>TimestampBehavior::className()
            ]
        ];
    }

    public function rules()
    {
        return [
            [['user_f', 'user_i', 'user_o', 'user_birth', 'phone', 'city', 'address', 'reason', 'complaint', 'visit_date'], 'required'],
            [['complaint'], 'string'],
            [['payment', 'dep_id', 'status1c', 'proposal_id', 'created_at', 'updated_at'], 'integer'],
            [['user_f', 'user_i', 'user_o', 'user_birth', 'phone', 'address', 'guide', 'reason', 'visit_date', 'visit_time', 'cost', 'who_calls', 'comment1c', 'created_by', 'updated_by','clinic','polis_oms_number','polis_org','city'], 'string', 'max'=>255],
            [['user_birth'], 'match', 'pattern'=>'/\d{2}.\d{2}.\d{4}/', 'message'=>'«{attribute}» должна быть в виде ХХ.ХX.XXXX']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_f'=>'Фамилия',
            'user_i'=>'Имя',
            'user_o'=>'Отчество',
            'user_birth'=>'Дата рождения',
            'phone'=>'Номер телефона',
            'who_calls'=>'ФИО, кто вызывал',
            'address'=>'Адрес',
            'city'=>'Город',
            'clinic'=>'Поликлника',
            'polis_oms_number'=>'Номер полюса ОМС',
            'polis_org'=>'Организация ОМС',
            'guide'=>'Ориентиры',
            'reason'=>'Повод',
            'complaint'=>'Жалобы',
            'visit_date'=>'Дата визита (план)',
            'payment'=>'Платный',
            'cost'=>'Стоимость',
            'dep_id'=>'Ответственное подразделение',
            'status1c'=>'Статус обработки 1С',
            'comment1c'=>'Комментарий обработки 1С',
            'proposal_id'=>'Заявка',
            'created_at'=>'Дата добавления',
            'updated_at'=>'Дата обновления',
            'created_by'=>'Заявку добавил(а)',
            'updated_by'=>'Заявку обработал(а)'
        ];
    }

    public function getCreater()
    {
        return $this->hasOne(Employee::className(), ['id'=>'created_by']);
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id'=>'dep_id']);
    }

    public function getProposal()
    {
        return $this->hasOne(UserProposal::className(), ['id'=>'proposal_id']);
    }

    public function getUpdater()
    {
        return $this->hasOne(Employee::className(), ['id'=>'updated_by']);
    }

}