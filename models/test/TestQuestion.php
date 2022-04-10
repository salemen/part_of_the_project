<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\validators\RequiredValidator;
use app\models\test\TestAnswer;

class TestQuestion extends ActiveRecord
{ 
    public $answers;
    
    public static function tableName()
    {
        return 'test_question';
    }
    
    public function rules()
    {
        return [
            [['answers'], 'validateAnswers'],
            [['name', 'group_id', 'test_id'], 'required'],
            [['group_id'], 'integer'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'test_id'=>'Тест',
            'group_id'=>'Группа',
            'name'=>'Название',
            'answers'=>'Варианты овтета'
        ];
    }
    
    public static function getAnswerList($question_id)
    {
        return ArrayHelper::map(TestAnswer::find()->where(['question_id'=>$question_id])->all(), 'id', 'name');
    }  
    
    public function afterSave($insert, $changedAttributes)
    {       
        if ($this->answers) {
            foreach ($this->answers as $answer) {   
                $model = (TestAnswer::findOne($answer['id'])) ?  : new TestAnswer(['question_id'=>$this->id]);
                
                if ($model->load($answer, '') && $model->save()) {
                    continue;
                }
                throw new ServerErrorHttpException();
            }
        }
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() 
    {
        $answers = $this->testAnswers;
   
        foreach ($answers as $answer) {
            $answer->delete();
        }
        
        return parent::beforeDelete();
    }
       
    public function getTestAnswers()
    {
        return $this->hasMany(TestAnswer::className(), ['question_id'=>'id']);
    }
    
    public function validateAnswers($attribute)
    {
        $require = new RequiredValidator();

        foreach ($this->$attribute as $index=>$row) {    
            $error = null;  
            $require->validate($row['name'], $error);
            if (!empty($error)) {
                $key = $attribute . '[' . $index . '][name]';
                $this->addError($key, 'Необходимо заполнить «Варианты ответа».');
            }
        }
        
        return true;
    }
}
