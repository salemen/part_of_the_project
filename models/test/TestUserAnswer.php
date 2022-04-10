<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;

class TestUserAnswer extends ActiveRecord
{ 
    public static function tableName()
    {
        return 'test_user_answer';
    }
    
    public function rules()
    {
        return [
            [['session_id', 'question_id', 'answer_id'], 'required'],
            [['session_id', 'question_id', 'answer_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'session_id'=>'Сессия',
            'question_id'=>'Вопрос',
            'answer_id'=>'Ответ'
        ];
    }
    
    public function getAnswer()
    {
        return $this->hasOne(TestAnswer::className(), ['id'=>'answer_id']);
    }
    
    public function getSession()
    {
        return $this->hasOne(TestUserSession::className(), ['id'=>'session_id']);
    }
    
    public function getQuestion()
    {
        return $this->hasOne(TestQuestion::className(), ['id'=>'question_id']);
    }
}

