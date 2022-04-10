<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeGroup extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_group';
    }
    
    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['group_id'], 'integer'],
            [['employee_id'], 'string', 'max'=>255],
            [['employee_id', 'group_id'], 'unique', 'targetAttribute'=>['employee_id', 'group_id']]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'employee_id'=>'Employee ID',
            'group_id'=>'Group ID'
        ];
    }
}