<?php
namespace app\models\oms;

use Yii;
use yii\db\ActiveRecord;

class oms extends ActiveRecord
{
    public static function tableName()
    {
        return 'oms_organisation';
    }

    public function rules()
    {
        return [

            [['oms', 'adress'], 'string', 'max'=>255],

        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'oms'=>'Организация',
            'adress'=>'Адрес',

        ];
    }
}