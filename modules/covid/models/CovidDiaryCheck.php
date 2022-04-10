<?php
namespace app\modules\covid\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CovidDiaryCheck extends ActiveRecord
{
    public static function tableName()
    {
        return 'covid_diary_check';
    }
    
    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }

    public function rules()
    {
        return [
            [['diary_id'], 'required'],
            [['diary_id', 'created_at'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'diary_id'=>'ID Дневника вакцинации',
            'created_at'=>'Дата заполнения'
        ];
    }
}