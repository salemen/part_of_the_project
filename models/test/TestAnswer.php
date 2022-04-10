<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;
use app\models\test\TestQuestion;

class TestAnswer extends ActiveRecord
{ 
    public static function tableName()
    {
        return 'test_answer';
    }
    
    public function rules()
    {
        return [
            [['name', 'cost', 'question_id'], 'required'],
            [['cost', 'question_id'], 'integer'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'question_id'=>'Вопрос',
            'name'=>'Название',
            'cost'=>'Балл'
        ];
    }
    
    public static function getTestId($question_id)
    {
        return TestQuestion::findOne($question_id)->test_id;
    }
}