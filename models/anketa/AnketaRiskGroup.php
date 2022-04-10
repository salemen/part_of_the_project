<?php
namespace app\models\anketa;

use Yii;
use yii\db\ActiveRecord;
use app\models\anketa\AnketaRiskCategory;
use app\models\anketa\AnketaRiskQuestion;

class AnketaRiskGroup extends ActiveRecord
{
    const NOTYPE = 0;
    const TYPE_AND = 10;
    const TYPE_OR = 20;
    const TYPE_SUM = 30;
        
    public static function tableName()
    {
        return 'anketa_risk_group';
    }
    
    public function rules()
    {
        return [
            [['category_id', 'tactic', 'risk_name', 'type'], 'required'],
            [['tactic'], 'string'],
            [['risk_name', 'operator'], 'string', 'max'=>255],
            [['category_id', 'type', 'sex', 'value'], 'integer']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'category_id'=>'Категория',
            'tactic'=>'Врачебная тактика',
            'risk_name'=>'Название риска',
            'type'=>'Тип условия',
            'sex'=>'Пол',
            'value'=>'Значение',
            'operator'=>'Оператор'
        ];
    }
    
    public function getAnketaRiskQuestions()
    {
        return $this->hasMany(AnketaRiskQuestion::className(), ['group_id'=>'id']);
    }

    public function beforeDelete()
    {
        $questions = $this->anketaRiskQuestions;
        
        foreach ($questions as $question) {
            $question->delete();
        }
               
        return parent::beforeDelete();
    }
    
    public static function getAnketaId($category_id)
    {
        return AnketaRiskCategory::find()->select('anketa_id')->where(['id'=>$category_id])->scalar();
    }
}

