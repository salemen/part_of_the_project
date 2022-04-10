<?php

namespace app\modules\b2b\models;

use app\models\cardio\Cardio;
use Yii;
use yii\db\ActiveRecord;
use app\models\data\Department;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\payments\Payments;
use app\models\consult\ConsultHistory;
use app\modules\b2b\models\ConsultType;

class Consult_one extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'consult';
    }


    public function rules()
    {
        return [
            [['employee_id', 'patient_id'], 'required'],
            [['dep_id', 'e_hide', 'p_hide', 'is_canceled', 'is_end', 'is_payd', 'is_special', 'created_at', 'ended_at'], 'integer'],
            [['employee_id', 'patient_id', 'comment'], 'string', 'max' => 255],
        ];
    }

   
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'employee_id' => 'Employee ID',
            'patient_id' => 'Patient ID',
            'comment' => 'Comment',
            'dep_id' => 'Dep ID',
            'e_hide' => 'E Hide',
            'p_hide' => 'P Hide',
            'is_canceled' => 'Is Canceled',
            'is_end' => 'Is End',
            'is_payd' => 'Is Payd',
            'is_special' => 'Is Special',
            'created_at' => 'Created At',
            'ended_at' => 'Ended At',
        ];
    }

    public function getDepartment()
    {
        return $this->hasOne(Department::className(), ['id'=>'dep_id']);
    }

    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }

    public function getEmployeePatient()
    {
        return $this->hasOne(Employee::className(), ['id'=>'patient_id']);
    }

    public function getHistory()
    {
        return $this->hasMany(ConsultHistory::className(), ['consult_id'=>'id']);
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'patient_id']);
    }

    public function getCardios()
    {
        return $this->hasMany(Cardio::className(), ['employee_id'=>'employee_id'])->alias('empl');
    }

    public function getConsult()
    {
        return $this->hasMany(Consult_one::className(), ['employee_id'=>'employee_id'])->alias('empl');
    }

    public function getContype()
    {
        return $this->hasMany(ConsultType::className(), ['consult'=>'is_special']);
    }

    public function getPayment()
    {
        return $this->hasOne(Payments::className(), ['orderNumber'=>'id'])
            ->andWhere(['orderType'=>Payments::TYPE_CONSULT]);
    }
}
