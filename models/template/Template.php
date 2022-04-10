<?php
namespace app\models\template;

use Yii;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;
use app\models\patient\Patient;

class Template extends ActiveRecord
{
    const TYPE_THERAPIST = 10;
    
    public static function tableName()
    {
        return 'template';
    }
    
    public function behaviors()
    {
        return [
            'blameable'=>[
                'class'=>BlameableBehavior::className(),
                'createdByAttribute'=>'employee_id',
                'updatedByAttribute'=>false
            ],
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['patient_id', 'type_id'], 'required'],
            [['type_id', 'created_at'], 'integer'],
            [['employee_id', 'patient_id'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'employee_id'=>'Доктор',
            'patient_id'=>'Пациент',
            'type_id'=>'Тип',
            'created_at'=>'Дата'
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }
    
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'patient_id']);
    }        
    
    public static function getRelatedModels()
    {
        return [
            self::TYPE_THERAPIST=>[
                'name'=>'Бланк осмотра терапевта',
                'className'=>types\Therapist::className(),
                'view'=>'therapist'
            ]
        ];
    }        
}