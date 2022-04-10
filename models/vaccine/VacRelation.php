<?php
namespace app\models\vaccine;

use Yii;
use yii\db\ActiveRecord;

class VacRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'vac_relation';
    }

    public function rules()
    {
        return [
            [['sick_id', 'vac_id'], 'required'],
            [['sick_id', 'vac_id'], 'integer'],
            [['sick_id', 'vac_id'], 'unique', 'targetAttribute'=>['sick_id', 'vac_id']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'sick_id'=>'Заболевание',
            'vac_id'=>'Вакцина'
        ];
    }
    
    public function getSickness()
    {
        return $this->hasOne(VacSickness::className(), ['id'=>'sick_id']);
    }   
    
    public function getVaccine()
    {
        return $this->hasOne(VacVaccine::className(), ['id'=>'vac_id']);
    } 
}