<?php
namespace app\models\other;

use Yii;
use yii\db\ActiveRecord;

class Mkb10 extends ActiveRecord
{
    public static function tableName()
    {
        return 'mkb10';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max'=>255],
            [['code'], 'string', 'max'=>32]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Диагноз МКБ',
            'code'=>'Код МКБ'
        ];
    }
}