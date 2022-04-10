<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;

class AnketaPermission extends ActiveRecord
{
    public static function tableName()
    {
        return 'anketa_permission';
    }

    public function rules()
    {
        return [
            [['anketa_id', 'param_name', 'value', 'operator'], 'required'],
            [['anketa_id'], 'integer'],
            [['param_name', 'value', 'operator'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'anketa_id'=>'ID анкеты',
            'param_name'=>'Название',
            'value'=>'Значение',
            'operator'=>'Условие'
        ];
    }
}

