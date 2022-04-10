<?php
namespace app\models\employee;

use Yii;
use yii\db\ActiveRecord;

class EmployeeDocumentOther extends ActiveRecord
{
    public static function tableName()
    {
        return 'employee_document_other';
    }

    public function rules()
    {
        return [
            [['employee_id'], 'required'],
            [['employee_id', 'empl_qual', 'empl_spec', 'doc_type', 'doc_valid'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'employee_id'=>'Сотрудник',
            'empl_qual'=>'Квалификация',
            'empl_spec'=>'Специализация',
            'doc_type'=>'Тип',
            'doc_valid'=>'Срок действия'
        ];
    }
}