<?php
namespace app\modules\covid\models;

use Yii;
use yii\db\ActiveRecord;

class HotlineDistrict extends ActiveRecord
{
    public static function tableName()
    {
        return 'hotline_district';
    }

    public function rules()
    {
        return [
            [['area_id', 'name'], 'required'],
            [['area_id'], 'integer'],
            [['content'], 'string'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'area_id'=>'Area ID',
            'name'=>'Name',
            'content'=>'Content'
        ];
    }
}