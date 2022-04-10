<?php
namespace app\models\user;

use Yii;
use yii\db\ActiveRecord;
use app\models\research\ResearchIndex;
use app\models\research\ResearchType;
use app\models\research\ResearchUnit;

class UserAnalysis extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
            
    public static function tableName()
    {
        return 'user_analysis';
    }

    public function rules()
    {
        return [
            [['type_id', 'index_id', 'unit_id', 'value', 'user_id', 'created_at'], 'required'],
            [['type_id', 'index_id', 'unit_id', 'is_lab', 'lab_id', 'lab_number', 'status', 'created_at'], 'integer'],
            [['value', 'user_id'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'type_id'=>'Вид исследования',
            'index_id'=>'Показатель',
            'unit_id'=>'Ед.измерения',
            'value'=>'Значение',
            'user_id'=>'Пациент',
            'is_lab'=>'Добавлено из лаборатории',
            'lab_id'=>'ID Исследования (лаборатория)',
            'lab_number'=>'ID Группы исследований (лаборатория)',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
    
    public function getResearchIndex()
    {
        return $this->hasOne(ResearchIndex::className(), ['id'=>'index_id']);
    } 
    
    public function getResearchType()
    {
        return $this->hasOne(ResearchType::className(), ['id'=>'type_id']);
    }     
    
    public function getResearchUnit()
    {
        return $this->hasOne(ResearchUnit::className(), ['id'=>'unit_id']);
    } 
}