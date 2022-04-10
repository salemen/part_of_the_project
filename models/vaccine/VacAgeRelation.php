<?php
namespace app\models\vaccine;

use Yii;
use yii\db\ActiveRecord;

class VacAgeRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'vac_age_relation';
    }

    public function rules()
    {
        return [
            [['age_id', 'sick_id'], 'required'],
            [['age_id', 'sick_id'], 'integer'],
            [['age_id', 'sick_id'], 'unique', 'targetAttribute'=>['age_id', 'sick_id']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'age_id'=>'Age ID',
            'sick_id'=>'Sick ID'
        ];
    }
    
    public function getAge()
    {
        return $this->hasOne(VacAge::className(), ['id'=>'age_id']);
    }    

    public function getSickness()
    {
        return $this->hasOne(VacSickness::className(), ['id'=>'sick_id']);
    }     
}