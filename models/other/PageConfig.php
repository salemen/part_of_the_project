<?php
namespace app\models\other;

use Yii;
use yii\db\ActiveRecord;

class PageConfig extends ActiveRecord
{
    public static function tableName()
    {
        return 'page_config';
    }

    public function rules()
    {
        return [
            [['page_url'], 'unique'],
            [['page_url'], 'required'],
            [['page_meta', 'page_url'], 'string', 'max'=>255],
            [['page_content'], 'string']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'page_content'=>'Описание страницы',
            'page_meta'=>'Мета-данные',
            'page_url'=>'Путь до страницы'
        ];
    }
}