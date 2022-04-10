<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;
use app\models\test\TestQuestion;

class TestGroup extends ActiveRecord
{ 
    public static function tableName()
    {
        return 'test_group';
    }
    
    public function beforeDelete() 
    {
        $questions = $this->testQuestions;
   
        foreach ($questions as $question) {
            $question->delete();
        }
        
        return parent::beforeDelete();
    }
      
    public function rules()
    {
        return [
            [['name', 'test_id'], 'required'],
            [['test_id'], 'integer'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'test_id'=>'Тест',
            'name'=>'Название'
        ];
    } 
    
    public function getTestQuestions()
    {
        return $this->hasMany(TestQuestion::className(), ['group_id'=>'id']);
    }
}

