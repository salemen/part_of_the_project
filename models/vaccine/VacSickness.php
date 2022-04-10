<?php
namespace app\models\vaccine;

use Yii;
use yii\db\ActiveRecord;

class VacSickness extends ActiveRecord
{
    public static function tableName()
    {
        return 'vac_sickness';
    }

    public function rules()
    {
        return [
            [['name'], 'required'],
            [['type'], 'integer'],
            [['name'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Name',
            'type'=>'Type'
        ];
    }
    
    public function getRelations()
    {
        return $this->hasMany(VacRelation::className(), ['sick_id'=>'id']);
    }        
    
    public static function getName($id)
    {
        $model = self::findOne(['id'=>$id]);
        
        return ($model) ? $model->name : null;
    }        
}