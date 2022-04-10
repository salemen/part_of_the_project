<?php
namespace app\models\vaccine;

use Yii;
use yii\db\ActiveRecord;

class VacAge extends ActiveRecord
{
    public $sick;
    
    public static function tableName()
    {
        return 'vac_age';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max'=>32]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Возраст'
        ];
    }
    
    public function getRelations()
    {
        return $this->hasMany(VacAgeRelation::className(), ['age_id'=>'id']);
    }       
}