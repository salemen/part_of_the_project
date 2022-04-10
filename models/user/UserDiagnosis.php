<?php
namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;

class UserDiagnosis extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_diagnosis';
    }
    
    public function afterFind()
    {
        $this->created_at = date('d.m.Y', $this->created_at);
        
        parent::afterFind();
    }
    
    public function beforeSave($insert) 
    {
        $this->created_at = date('U', strtotime($this->created_at));
        
        return parent::beforeSave($insert);
    }

    public function rules()
    {
        return [
            [['patient_id', 'diagnosis', 'created_at'], 'required'],
            [['diagnosis', 'comment'], 'string'],            
            [['employee', 'patient_id'], 'string', 'max'=>255],
            [['created_at'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'employee'=>'Специалист',
            'patient_id'=>'Пациент',
            'diagnosis'=>'Код / Диагноз МКБ',
            'comment'=>'Рекомендации специалиста / Комментарий',
            'created_at'=>'Дата'
        ];
    }       
    
    public static function getDiagnosisCount($diagnosis, $patient_id, $month)
    {
        return UserDiagnosis::find()->select(['*', 'FROM_UNIXTIME(created_at, "%m.%Y") AS month'])
            ->where(['diagnosis'=>$diagnosis, 'patient_id'=>$patient_id])
            ->having(['month'=>$month])  
            ->count();
    }
}