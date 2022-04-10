<?php
namespace app\models\menu;

use Yii;
use yii\db\ActiveRecord;

class MenuSection extends ActiveRecord
{
    public static function tableName()
    {
        return 'menu_section';
    }

    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['is_hide_all', 'is_on_header', 'is_on_mega', 'is_on_footer', 'status'], 'integer'],
            [['name', 'slug'], 'string', 'max'=>255],
            [['slug'], 'unique']
        ];
    }

    public function attributeLabels()
    {
        return [
            'id'=>'ID',
            'name'=>'Название',
            'slug'=>'Slug',
            'is_hide_all'=>'Скрывать пункт меню "Показать все"',
            'is_on_header'=>'Отображать в главном меню',
            'is_on_mega'=>'Отображать в "мега" меню',
            'is_on_footer'=>'Отображать в подвале',
            'status'=>'Статус'
        ];
    }
    
    public function getMenuItems()
    {
        return $this->hasMany(MenuItem::className(), ['section_id'=>'id'])
            ->andWhere(['status'=>10])
            ->orderBy('id');
    }        
}