<?php
namespace app\models\checker;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CheckerSymptoms extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    
    public $specs;
    
    public static function tableName()
    {
        return 'checker_symptoms';
    }
    
    public function afterFind()
    {
        $this->specs = ArrayHelper::getColumn($this->specialities, 'speciality');
        
        parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        CheckerSymptomsSpecialities::deleteAll(['symptom_id'=>$this->id]);
        
        if ($this->specs) {
            foreach ($this->specs as $speciality) {
                (new CheckerSymptomsSpecialities(['symptom_id'=>$this->id, 'speciality'=>$speciality]))->save();
            }
        }
        
        parent::afterSave($insert, $changedAttributes);
    }
    
    public function beforeDelete() 
    {
        $relations = [
            $this->specialities,
            $this->symptomRelations
        ];
        
        foreach ($relations as $relation) {
            if ($relation) {
                foreach ($relation as $el) {
                    $el->delete();
                }
            }
        }
        
        return parent::beforeDelete();
    }
    
    public function behaviors()
    {
        return [
            'sluggable'=>[
                'class'=>SluggableBehavior::className(),
                'attribute'=>'name',
                'ensureUnique'=>true
            ]
        ];
    }

    public function rules()
    {
        return [
            [['status'], 'integer'],
            [['name', 'slug'], 'required'],
            [['name', 'slug'], 'string', 'max'=>255],
            [['content'], 'string'],
            [['name'], 'unique'],
            [['specs'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование',
            'slug'=>'Slug',
            'content'=>'Контент',
            'specs'=>'Специальности',
            'status'=>'Статус'
        ];
    }
    
    public function getSpecialities()
    {
        return $this->hasMany(CheckerSymptomsSpecialities::className(), ['symptom_id'=>'id']);
    }
    
    public function getSymptomRelations()
    {
        return $this->hasMany(CheckerRelation::className(), ['symptom_id'=>'id']);
    }
}