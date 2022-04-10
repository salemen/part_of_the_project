<?php
namespace app\models\checker;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class CheckerBodyparts extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_INACTIVE = 0;
    
    public $sections;
    
    public static function tableName()
    {
        return 'checker_bodyparts';
    }
    
    public function afterFind()
    {
        $this->sections = ArrayHelper::getColumn($this->bodypartSections, 'section_id');
        
        parent::afterFind();
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        CheckerBodypartsSections::deleteAll(['bodypart_id'=>$this->id]);
        
        if ($this->sections) {
            foreach ($this->sections as $section) {
                (new CheckerBodypartsSections(['bodypart_id'=>$this->id, 'section_id'=>$section]))->save();
            }
        }
        
        parent::afterSave($insert, $changedAttributes);
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sex_m', 'sex_w', 'status'], 'integer'],
            [['name'], 'string', 'max'=>255],
            [['sections'], 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование',
            'sections'=>'Медицинские направления',
            'sex_m'=>'Для мужского пола',
            'sex_w'=>'Для женского пола',
            'status'=>'Статус'
        ];
    }
    
    public function getBodypartSections()
    {
        return $this->hasMany(CheckerBodypartsSections::className(), ['bodypart_id'=>'id']);
    }     
}