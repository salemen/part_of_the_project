<?php
namespace app\models\research;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ResearchNormsCol extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_norms_col';
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
            [['index_id', 'unit_id', 'norm_m_min', 'norm_m_max', 'norm_w_min', 'norm_w_max', 'norm_min', 'norm_max'], 'required'],
            [['index_id', 'unit_id', 'status', 'created_at'], 'integer'],
            [['norm_m_min', 'norm_m_max', 'norm_w_min', 'norm_w_max', 'norm_pr_min', 'norm_pr_max', 'norm_min', 'norm_max'], 'string', 'max'=>255],
            [['index_id', 'unit_id'], 'unique', 'targetAttribute'=>['index_id', 'unit_id'], 'message'=>'Норма с выбранной единицей измерения уже существует']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'index_id'=>'Показатель',
            'unit_id'=>'Единица измерения',
            'norm_m_min'=>'Норма муж. (min)',
            'norm_m_max'=>'Норма муж. (max)',
            'norm_w_min'=>'Норма жен. (min)',
            'norm_w_max'=>'Норма жен. (max)',
            'norm_pr_min'=>'Норма берем. (min)',
            'norm_pr_max'=>'Норма берем. (max)',
            'norm_min'=>'Норма (min возможная)',
            'norm_max'=>'Норма (max возможная)',
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