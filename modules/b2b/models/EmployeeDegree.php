<?php

namespace app\modules\b2b\models;

use Yii;


class EmployeeDegree extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'employee_degree';
    }


    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'empl_rank', 'empl_degree'], 'string', 'max' => 255],
        ];
    }

    
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'employee_id' => 'ID Врача',
            'empl_rank' => 'Ранг',
            'empl_degree' => 'Степень',
        ];
    }
}
