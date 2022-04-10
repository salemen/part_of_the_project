<?php
namespace app\models\geo;

use Yii;
use yii\db\ActiveRecord;

class GeoDistrict extends ActiveRecord
{
    public static function tableName()
    {
        return 'geo_district';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Федеральный округ'
        ];
    }
}