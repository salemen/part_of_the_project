<?php
namespace app\models\geo;

use Yii;
use yii\db\ActiveRecord;

class GeoRegion extends ActiveRecord
{
    public static function tableName()
    {
        return 'geo_region';
    }

    public function rules()
    {
        return [
            [['district_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'district_id'=>'Федеральный округ',
            'name'=>'Регион'
        ];
    }
    
    public function getDistrict()
    {
        return $this->hasOne(GeoDistrict::className(), ['id'=>'district_id']);
    }
}
