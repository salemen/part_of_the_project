<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeAttributes extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_attributes';
    }

    public function rules()
    {
        return [
            [['is_director', 'is_official', 'is_santal', 'is_visor'], 'integer'],
            [['employee_id'], 'string', 'max'=>255],
            [['employee_id'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'employee_id'=>'ID Сотрудника',
            'is_director'=>'Руководитель',
            'is_santal'=>'Сотрудник ГК САНТАЛЬ',
            'is_official'=>'Официальное трудоустройство',
            'is_visor'=>'Куратор'
        ];
    }
    
    public static function primaryKey()
    {
        return ['employee_id'];
    }
}