<?php
namespace app\models\research;

use Yii;
use yii\db\ActiveRecord;

class ResearchLabRelation extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_lab_relation';
    }

    public function rules()
    {
        return [
            [['type_id', 'index_id'], 'required'],
            [['type_id', 'index_id'], 'integer'],
            [['type_id', 'index_id'], 'unique', 'targetAttribute'=>['type_id', 'index_id']]
        ];
    }

    public function attributeLabels()
    {
        return [
            'type_id'=>'Type ID',
            'index_id'=>'Index ID'
        ];
    }
    
    public function getLabIndex()
    {
        return $this->hasOne(ResearchLabIndex::className(), ['id'=>'index_id']);
    }     
    
    public function getLabType()
    {
        return $this->hasOne(ResearchLabType::className(), ['id'=>'type_id']);
    }
}
