<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeDegree extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_degree';
    }

    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'empl_rank', 'empl_degree'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'employee_id'=>'Сотрудник',
            'empl_rank'=>'Должность',
            'empl_degree'=>'Степень'
        ];
    }
}