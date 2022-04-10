<?php
namespace app\models\cardio;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\consult\Consult;

class Cardio extends ActiveRecord
{    
    public static function tableName()
    {
        return 'cardio';
    }
    
    public function beforeDelete()
    {               
        if ($this->cardioDocs) {
            foreach ($this->cardioDocs as $cardioDoc) {
                unlink('uploads/' . $cardioDoc->file);
                $cardioDoc->delete();
            }
        }
        
        return parent::beforeDelete();
    }
    
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['ekg_date', 'patient_id'], 'required'],
            [['patient_sicks', 'patient_drugs', 'patient_target'], 'string'],
            [['is_end', 'is_payd', 'created_at'], 'integer'],
            [['ekg_date', 'employee_id', 'patient_id', 'patient_height', 'patient_weight'], 'string', 'max'=>255]          
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'ekg_date'=>'Дата снятия ЭКГ',
            'employee_id'=>'Сотрудник',
            'patient_id'=>'Пациент',            
            'patient_height'=>'Рост',
            'patient_weight'=>'Вес',
            'patient_sicks'=>'Жалобы / Наличие заболеваний',
            'patient_drugs'=>'Принимаемые лекарственные препараты',
            'patient_target'=>'Цель регистрации ЭКГ',          
            'is_end'=>'Завершена',
            'is_payd'=>'Оплачена',
            'created_at'=>'Дата'
        ];
    }
    
    public function getCardioDocs()
    {
        return $this->hasMany(CardioDocs::className(), ['cardio_id'=>'id']);
    }   
    
    public function getCardioResult()
    {
        return $this->hasMany(CardioResult::className(), ['cardio_id'=>'id']);
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'employee_id']);
    }
    
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'patient_id']);
    }

    public function getConsult()
    {
        return $this->hasOne(Consult::className(), ['employee_id'=>'employee_id']);
    }
}