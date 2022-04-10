<?php
namespace app\models\research;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ResearchMethod extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_method';
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
}