<?php
namespace app\models\vaccine;

use Yii;
use yii\db\ActiveRecord;

class VacVaccine extends ActiveRecord
{
    public static function tableName()
    {
        return 'vac_vaccine';
    }

    public function rules()
    {
        return [
            [['name', 'dest', 'state'], 'required'],
            [['type'], 'integer'],
            [['name', 'dest', 'state', 'org'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Name',
            'dest'=>'Dest',
            'state'=>'State',
            'org'=>'Org',
            'type'=>'Type'
        ];
    }
    
    public function getRelations()
    {
        return $this->hasMany(VacRelation::className(), ['vac_id'=>'id']);
    } 
}