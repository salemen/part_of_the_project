<?php
namespace app\models\research;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class ResearchType extends ActiveRecord
{
    public static function tableName()
    {
        return 'research_type';
    }

    public function behaviors()
    {
        return [
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ],
            'uploadBehavior'=>[
                'class'=>UploadBehavior::className(),
                'attributes'=>[
                    'icon'=>[
                        'path'=>'@storage/research-type',
                        'tempPath'=>'temp',
                        'url'=>false
                    ]
                ]
            ]
        ];
    }
    
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['rel_id', 'status', 'created_at'], 'integer'],
            [['name', 'name_alt', 'icon'], 'string', 'max'=>255],
            [['name'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Наименование',
            'name_alt'=>'Альтернативное наименование',
            'rel_id'=>'Лабораторное значение',
            'icon'=>'Иконка',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
    
    public function getRel()
    {
        return $this->hasOne(ResearchLabType::className(), ['id'=>'rel_id']);
    }        
}