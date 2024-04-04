<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;

class AnketaUserAnswer extends ActiveRecord
{
    public static function tableName()
    {
        return 'anketa_user_answer';
    }

    public function rules()
    {
        return [
            [['session_id', 'question_id', 'answer'], 'required'],
            [['session_id', 'question_id'], 'integer'],
            [['answer'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'id',
            'session_id'=>'ID сессии',
            'question_id'=>'ID вопроса',
            'answer'=>'Ответ'
        ];
    }
}