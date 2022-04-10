<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;
use app\models\anketa\AnketaRiskCategory;
use app\models\anketa\AnketaRiskGroup;

class AnketaRiskQuestion extends ActiveRecord
{ 
    public static function tableName()
    {
        return 'anketa_risk_question';
    }
    
    public function rules()
    {
        return [
            [['group_id', 'question_id'], 'required'],
            [['group_id', 'question_id', 'answer_id'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'group_id'=>'Группа',
            'question_id'=>'Вопрос',
            'answer_id'=>'Ответ',
        ];
    }
    
    public static function getCategoryId($group_id)
    {
        return AnketaRiskGroup::find()->select('category_id')->where(['id'=>$group_id])->scalar();
    }
    
    public static function getAnketaId($group_id)
    {
        $category_id = self::getCategoryId($group_id);
        
        return AnketaRiskCategory::find()->select('anketa_id')->where(['id'=>$category_id])->scalar();
    }
}

