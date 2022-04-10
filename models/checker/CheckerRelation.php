<?php
namespace app\models\checker;

use Yii;
use yii\db\ActiveRecord;

class CheckerRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'checker_relation';
    }

    public function rules()
    {
        return [
            [['bodypart_id', 'symptom_id'], 'required'],
            [['bodypart_id', 'symptom_id'], 'integer'],
            [['bodypart_id', 'symptom_id'], 'unique', 'targetAttribute'=>['bodypart_id', 'symptom_id'], 'message'=>'Для выбранной части тела уже существует такой симптом.']
        ];
    }

    public function attributeLabels()
    {
        return [
            'bodypart_id'=>'Категория',
            'symptom_id'=>'Симптом'
        ];
    }
    
    public function getBodypart()
    {
        return $this->hasOne(CheckerBodyparts::className(), ['id'=>'bodypart_id']);
    }
    
    public function getSymptom()
    {
        return $this->hasOne(CheckerSymptoms::className(), ['id'=>'symptom_id']);
    } 
}