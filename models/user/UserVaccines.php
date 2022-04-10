<?php
namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;

class UserVaccines extends ActiveRecord
{
    public static function tableName()
    {
        return 'user_vaccines';
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
            [['patient_id', 'vaccine', 'created_at'], 'required'],            
            [['employee', 'patient_id', 'vaccine'], 'string', 'max'=>255],
            [['comment'], 'string'],
            [['created_at'], 'safe']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'employee'=>'Специалист',
            'patient_id'=>'Пациент',
            'vaccine'=>'Вакцина',
            'comment'=>'Комментарий',
            'created_at'=>'Дата'
        ];
    }
}