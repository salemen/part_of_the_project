<?php
namespace app\modules\covid\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CovidMaps extends ActiveRecord
{
    public static function tableName()
    {
        return 'covid_maps';
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
            [['name'], 'required'],
            [['covid_hospital', 'covid_test', 'covid_vaccine'], 'string'],
            [['status', 'created_at'], 'integer'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Заголовок',
            'covid_hospital'=>'Респираторные госпитали',
            'covid_test'=>'Где сделать КТ',
            'covid_vaccine'=>'Где сделать тест на COVID-19',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
}