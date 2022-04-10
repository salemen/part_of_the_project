<?php
namespace app\models\research;

use Yii;
use yii\db\ActiveRecord;

class ResearchLabType extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_lab_type';
    }

    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['name'], 'required'],
            [['name'], 'string', 'max'=>255],
            [['id'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Name'
        ];
    }     
    
    public static function primaryKey()
    {
        return ['id'];
    }
    
    public function getLabRelation()
    {
        return $this->hasMany(ResearchLabRelation::className(), ['type_id'=>'id']);
    }
}