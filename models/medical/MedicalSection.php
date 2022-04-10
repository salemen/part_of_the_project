<?php
namespace app\models\medical;

use Yii;
use yii\db\ActiveRecord;

class MedicalSection extends ActiveRecord
{
    public static function tableName()
    {
        return 'medical_section';
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['status'], 'integer'],
            [['name', 'slug'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Название',
            'slug'=>'Slug',
            'status'=>'Статус'
        ];
    }
}