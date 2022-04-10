<?php
namespace app\models\research;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ResearchUnit extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_unit';
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
            [['name'], 'required'],
            [['status', 'created_at'], 'integer'],
            [['name'], 'string', 'max'=>255],
            [['name'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
    
    public function getResearchNormsCol()
    {
        return $this->hasMany(ResearchNormsCol::className(), ['unit_id'=>'id']);
    }
    
    public function getResearchNormsQual()
    {
        return $this->hasMany(ResearchNormsQual::className(), ['unit_id'=>'id']);
    }
    
    public static function getUnitName($id)
    {
        $model = self::findOne($id);
        
        if ($model) {
            return ($model->id === 28) ? null : $model->name;
        }
        
        return null;
    }        
}