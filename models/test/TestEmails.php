<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;

class TestEmails extends ActiveRecord
{
    public static function tableName()
    {
        return 'test_emails';
    }

    public function rules()
    {
        return [
            [['test_id', 'email'], 'required'],
            [['test_id'], 'integer'],
            [['email'], 'string', 'max'=>255],
            [['test_id', 'email'], 'unique', 'targetAttribute'=>['test_id', 'email']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'test_id'=>'Test ID',
            'email'=>'Email'
        ];
    }
    
    public function getTest()
    {
        return $this->hasOne(Test::className(), ['id'=>'test_id']);
    }        
}