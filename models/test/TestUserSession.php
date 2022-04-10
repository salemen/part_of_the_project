<?php
namespace app\models\test;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use app\models\employee\Employee;
use app\models\patient\Patient;
use app\models\test\TestUserAnswer;

class TestUserSession extends ActiveRecord
{ 
    public static function tableName()
    {
        return 'test_user_session';
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
            [['user_id', 'test_id'], 'required'],
            [['user_id'], 'string'],
            [['created_at'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'user_id'=>'Пользователь',
            'test_id'=>'Тест',
            'created_at'=>'Дата'
        ];
    }
    
    public function getEmployee()
    {
        return $this->hasOne(Employee::className(), ['id'=>'user_id']);
    }

    public function getPatient()
    {
        return $this->hasOne(Patient::className(), ['id'=>'user_id']);
    }
    
    public function getTest()
    {
        return $this->hasOne(Test::className(), ['id'=>'test_id']);
    }        
    
    public function getTestUserAnswer()
    {
        return $this->hasMany(TestUserAnswer::className(), ['session_id'=>'id']);
    }
}

