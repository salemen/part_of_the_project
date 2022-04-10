<?php
namespace app\models\checker;

use Yii;
use yii\db\ActiveRecord;
use app\models\medical\MedicalSection;

class CheckerBodypartsSections extends ActiveRecord
{
    public static function tableName()
    {
        return 'checker_bodyparts_sections';
    }

    public function rules()
    {
        return [
            [['bodypart_id', 'section_id'], 'required'],
            [['bodypart_id', 'section_id'], 'integer'],
            [['bodypart_id', 'section_id'], 'unique', 'targetAttribute'=>['bodypart_id', 'section_id']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'bodypart_id'=>'Bodypart ID',
            'section_id'=>'Section ID'
        ];
    }
    
    public function getBodypart()
    {
        return $this->hasOne(CheckerBodyparts::className(), ['id'=>'bodypart_id']);
    }
    
    public function getSection()
    {
        return $this->hasOne(MedicalSection::className(), ['id'=>'section_id']);
    }
}