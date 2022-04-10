<?php

namespace app\modules\b2b\models;

use Yii;


class ConsultType extends \yii\db\ActiveRecord
{

    public static function tableName()
    {
        return 'consult_type';
    }


    public function rules()
    {
        return [
            [['consult', 'type'], 'required'],
            [['consult'], 'integer'],
            [['type'], 'string', 'max' => 50],
        ];
    }

    
    public function attributeLabels()
    {
        return [
            '1' => '1',
            'consult' => 'Consult',
            'type' => 'Type',
        ];
    }
}
