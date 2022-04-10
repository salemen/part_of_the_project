<?php
namespace app\models\data;

use Yii;
use yii\db\ActiveRecord;

class Group extends ActiveRecord
{
    public static function tableName()
    {
        return 'data_group';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['parent_id'], 'integer'],
            [['name'], 'string', 'max'=>255]            
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование',
            'parent_id'=>'Родитель'
        ];
    }
    
    public static function findByPosition($position)
    {
        $name = mb_strtolower($position, 'UTF-8');
        
        $model = self::findOne(['name'=>$name]);
        
        if ($model) {
            return $model->parent_id;
        }
        
        if (preg_match('/^врач/', $name)) {
            return self::findOne(['name'=>'Врачебный персонал'])->id;
        }
        
        return self::findOne(['name'=>'Другое'])->id;
    }
}