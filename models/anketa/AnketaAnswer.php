<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;

class AnketaAnswer extends ActiveRecord
{
    public static function tableName()
    {
        return 'anketa_answer';
    }

    public function rules()
    {
        return [
            [['question_id', 'name'], 'required'],
            [['question_id', 'cost'], 'integer'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'question_id'=>'ID вопроса',
            'name'=>'Текст ответа',
            'cost'=>'Баллы'
        ];
    }
    
    public function getAnketaQuestion()
    {
        return $this->hasOne(AnketaQuestion::className(), ['id'=>'question_id']);
    }
}