<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;

class TestResult extends ActiveRecord
{ 
    public static function tableName()
    {
        return 'test_result';
    }
    
    public function rules()
    {
        return [
            [['session_id', 'group_id', 'result'], 'required'],
            [['session_id', 'group_id', 'result'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'session_id'=>'Сессия',
            'group_id'=>'Группа',
            'result'=>'Результат'
        ];
    }
}

