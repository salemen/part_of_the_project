<?php
namespace app\models\checker;

use Yii;
use yii\db\ActiveRecord;

class CheckerSymptomsSpecialities extends ActiveRecord
{
    public static function tableName()
    {
        return 'checker_symptoms_specialities';
    }

    public function rules()
    {
        return [
            [['symptom_id', 'speciality'], 'required'],
            [['symptom_id'], 'integer'],
            [['speciality'], 'string', 'max'=>255],
            [['symptom_id', 'speciality'], 'unique', 'targetAttribute'=>['symptom_id', 'speciality']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'symptom_id'=>'Симптом',
            'speciality'=>'Специальность'
        ];
    }
}