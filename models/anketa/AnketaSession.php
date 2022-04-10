<?php
namespace app\models\anketa;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;
use app\models\patient\Patient;

class AnketaSession extends ActiveRecord
{
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }
    
    public static function tableName()
    {
        return 'anketa_session';
    }

    public function rules()
    {
        return [
            [['anketa_id', 'patient_id'], 'required'],
            [['anketa_id', 'is_end', 'created_at'], 'integer'],
            [['patient_id'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'anketa_id'=>'ID анкеты',
            'patient_id'=>'ID пациента',
            'is_end'=>'Анкета заполнена полностью',
            'created_at'=>'Дата'
        ];
    }
    
    public function getAnketa()
    {
        return $this->hasOne(Anketa::className(), ['id'=>'anketa_id']);
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'patient_id']);
    }
    
    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'patient_id']);
    }
    
    public static function getAnketasCount($user_id)
    {
        return self::find()->where(['patient_id'=>$user_id, 'is_end'=>true])->count();
    }
}