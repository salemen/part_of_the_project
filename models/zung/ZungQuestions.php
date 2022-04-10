<?php
namespace app\models\zung;

use Yii;
use yii\db\ActiveRecord;

class ZungQuestions extends ActiveRecord
{
    public static function tableName()
    {
        return 'questions';
    }

    public static function getDb()
    {
        return Yii::$app->get('db_zung');
    }

    public function rules()
    {
        return [
            [['question'], 'required'],
            [['question'], 'string', 'max'=>1000]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'question'=>'Question',
        ];
    }
    
    public static function getAnswerList()
    {
        return [
            1=>'Никогда или изредка',
            2=>'Иногда',
            3=>'Часто',
            4=>'Почти всегда или постоянно'
        ];
    }
}