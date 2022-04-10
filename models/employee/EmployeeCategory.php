<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeCategory extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_category';
    }

    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'empl_cat', 'empl_spec'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'employee_id'=>'Сотрудник',
            'empl_cat'=>'Категория',
            'empl_spec'=>'Специальность'
        ];
    }
}