<?php
namespace app\modules\covid\models;

use Yii;
use yii\db\ActiveRecord;

class CovidDiaryAnswers extends ActiveRecord
{
    public static function tableName()
    {
        return 'covid_diary_answers';
    }
    
    public function rules()
    {
        return [
            [['check_id', 'answer', 'question_type'], 'required'],
            [['check_id'], 'integer'],
            [['question_type', 'answer'], 'string', 'max'=>255]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'check_id'=>'Check ID',
            'question_type'=>'Тип вопроса',
            'answer'=>'Answer'
        ];
    }
}