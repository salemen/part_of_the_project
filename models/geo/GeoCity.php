<?php
namespace app\models\geo;

use Yii;
use yii\db\ActiveRecord;

class GeoCity extends ActiveRecord
{
    public static function tableName()
    {
        return 'geo_city';
    }

    public function rules()
    {
        return [
            [['region_id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'region_id'=>'Регион',
            'name'=>'Город'
        ];
    }
    
    public function getRegion()
    {
        return $this->hasOne(GeoRegion::className(), ['id'=>'region_id']);
    }        
}