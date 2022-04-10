<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;
use app\models\anketa\AnketaRiskGroup;

class AnketaRiskCategory extends ActiveRecord
{  
    public static function tableName()
    {
        return 'anketa_risk_category';
    }
    
    public function rules()
    {
        return [
            [['name', 'anketa_id'], 'required'],
            [['name'], 'string', 'max'=>255],
            [['anketa_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'anketa_id'=>'Анкета',
            'name'=>'Название'
        ];
    }
    
    public function getAnketaRiskGroups()
    {
        return $this->hasMany(AnketaRiskGroup::className(), ['category_id'=>'id']);
    }
    
    public function beforeDelete()
    {
        $groups = $this->anketaRiskGroups;
        
        foreach ($groups as $group) {
            $group->delete();
        }
        
        return parent::beforeDelete();
    }
}

