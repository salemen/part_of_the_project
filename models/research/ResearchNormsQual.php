<?php
namespace app\models\research;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ResearchNormsQual extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_norms_qual';
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
            [['index_id', 'unit_id', 'norm_value'], 'required'],
            [['index_id', 'unit_id', 'is_norm', 'status', 'created_at'], 'integer'],
            [['norm_value'], 'string', 'max'=>255],
            [['interp'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'index_id'=>'Показатель',
            'unit_id'=>'Единица измерения',
            'norm_value'=>'Значение',
            'interp'=>'Интерпреация',
            'is_norm'=>'Является нормой',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
    
    public function getIndex()
    {
        return $this->hasOne(ResearchIndex::className(), ['id'=>'index_id']);
    }     
    
    public function getUnit()
    {
        return $this->hasOne(ResearchUnit::className(), ['id'=>'unit_id']);
    }
}