<?php
namespace app\models\test;

use Yii;
use yii\db\ActiveRecord;

class TestHearing extends ActiveRecord
{
    public static function tableName()
    {
        return 'test_hearing';
    }

    public function rules()
    {
        return [
            [['id'], 'required'],
            [['phone', 'user_id', 'result'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'result'=>'Результат',
            'phone'=>'Телефон',
            'user_id'=>'Юзер'
        ];
    }

}