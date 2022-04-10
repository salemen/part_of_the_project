<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeCustom extends ActiveRecord
{
    const TYPE_POSITION = 10;
    
    public static function tableName()
    {
        return 'employee_custom';
    }

    public function rules()
    {
        return [
            [['type', 'employee_id', 'value'], 'required'],
            [['type'], 'integer'],
            [['employee_id', 'value'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'type'=>'Тип',
            'employee_id'=>'Сотрудник',
            'value'=>'Значение'
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }
}