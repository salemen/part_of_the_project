<?php
// Форма интерпретации результатов анализов

namespace app\forms;

use Yii;
use yii\base\Model;

class InterpForm extends Model
{    
    public $user_birthday;
    public $user_fullname;    
    public $user_sex;    
    public $is_pregnant;
    public $research_date;
    public $values;
    
    public function rules()
    {
        return [
            [['user_birthday', 'user_fullname', 'user_sex', 'research_date'], 'required'],
            [['user_birthday', 'user_fullname', 'research_date'], 'string'],
            [['user_sex', 'is_pregnant'], 'integer'],
            [['values'], 'safe']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'user_birthday'=>'Дата рождения (возраст)',
            'user_fullname'=>'ФИО',
            'user_sex'=>'Пол',
            'is_pregnant'=>'Беременность',
            'research_date'=>'Дата исследования',
            'values'=>'Показатели'
        ];
    }
}