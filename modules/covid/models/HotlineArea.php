<?php
namespace app\modules\covid\models;

use Yii;
use yii\db\ActiveRecord;

class HotlineArea extends ActiveRecord
{
    public static function tableName()
    {
        return 'hotline_area';
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
            'name'=>'Name'
        ];
    }
    
    public function getDistricts()
    {
        return $this->hasMany(HotlineDistrict::className(), ['area_id'=>'id']);
    }        
}