<?php
namespace app\models\other;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class Slider extends ActiveRecord
{
    public static function tableName()
    {
        return 'slider';
    }
    
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $max_pos = self::find()->max('position');
                $order = ($max_pos == null) ? 0 : $max_pos + 1;
                $this->updateAttributes(['position'=>$order]);
            }
            return true;
        } else {
            return false;
        }
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
                    'file'=>[
                        'path'=>'uploads',
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
            [['name', 'file'], 'required'],
            [['show_main', 'show_research', 'created_at'], 'integer'],
            [['file', 'url_href'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Название',
            'file'=>'Файл',
            'url_href'=>'Ссылка',
            'show_main'=>'Показывать на главной',
            'show_research'=>'Показывать на странице "Просмотр результатов анализов"',
            'position'=>'Сортировка',
            'created_at'=>'Дата'
        ];
    }
}