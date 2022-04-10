<?php
namespace app\modules\covid\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;

class CovidPages extends ActiveRecord
{
    const STATUS_ACTIVE = 10;
    const STATUS_DELETE = 0;
    
    public static function tableName()
    {
        return 'covid_pages';
    }

    public function behaviors()
    {
        return [
            'sluggable'=>[
                'class'=>SluggableBehavior::className(),
                'attribute'=>'name',
                'ensureUnique'=>true,
                'slugAttribute'=>'action'
            ],
            'timestamp'=>[
                'class'=>TimestampBehavior::className(),
                'updatedAtAttribute'=>false
            ]
        ];
    }
    
    public function rules()
    {
        return [
            [['controller', 'name', 'content'], 'required'],
            [['content'], 'string'],
            [['status', 'created_at'], 'integer'],
            [['controller', 'action', 'name'], 'string', 'max'=>255],
            [['name'], 'unique', 'targetAttribute'=>['controller', 'name'], 'message'=>'Данное имя уже существует в указанном разделе']
        ];
    }

    public function attributeLabels()
    {
        return [
            'controller'=>'Раздел',
            'action'=>'Метод',
            'name'=>'Заголовок',
            'content'=>'Описание',
            'status'=>'Статус',
            'created_at'=>'Дата'
        ];
    }
    
    public static function controllerArray()
    {
        return [
            'faq'=>'Актуальные вопросы и ответы',
            'hospital'=>'Госпитализация'
        ];
    }     

    public static function isPagesExists($controller)
    {
        return self::find()->where(['controller'=>$controller, 'status'=>10])->exists();
    }       
}