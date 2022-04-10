<?php
namespace app\models\menu;

use Yii;
use vova07\fileapi\behaviors\UploadBehavior;
use yii\db\ActiveRecord;

class MenuItem extends ActiveRecord
{
    public static function tableName()
    {
        return 'menu_item';
    }
    
    public function behaviors()
    {
        return [
            'uploadBehavior'=>[
                'class'=>UploadBehavior::className(),
                'attributes'=>[
                    'photo'=>[
                        'path'=>'@storage/menu-item',
                        'tempPath'=>'temp',
                        'url'=>false
                    ],
                    'photo_small'=>[
                        'path'=>'@storage/menu-item-small',
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
            [['section_id', 'name', 'url'], 'required'],
            [['section_id', 'is_blank', 'is_on_header', 'is_on_mega', 'is_on_footer', 'status'], 'integer'],
            [['name', 'url', 'class_default', 'class_guest', 'photo', 'photo_small'], 'string', 'max'=>255]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'section_id'=>'Раздел',
            'name'=>'Название',
            'url'=>'Ссылка',
            'class_default'=>'Класс по-умолчанию',
            'class_guest'=>'Класс для неавторизованных пользователей',
            'photo'=>'Изображение',
            'photo_small'=>'Изображение (маленькое)',
            'is_blank'=>'Открывать в новой вкладке',
            'is_on_header'=>'Отображать в главном меню',
            'is_on_mega'=>'Отображать в "мега" меню',
            'is_on_footer'=>'Отображать в подвале',
            'status'=>'Статус'
        ];
    }
    
    public function getSection()
    {
        return $this->hasOne(MenuSection::className(), ['id'=>'section_id']);
    }        
}