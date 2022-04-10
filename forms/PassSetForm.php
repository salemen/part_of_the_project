<?php
// Форма изменения пароля (из ЛК)
// TODO Перенести в модуль user

namespace app\forms;

use Yii;
use yii\base\Model;
use app\models\patient\Patient;

class PassSetForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            [['password'], 'required'],
            [['password'], 'string', 'min'=>6]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'password'=>'Пароль'
        ];
    }
    
    public function save()
    {
        if (!$this->validate()) { return null; }
        
        $user = Yii::$app->user->identity;
        
        if ($user instanceof Patient) {
            $user->setPassword($this->password);
            return $user->save();
        }

        return false;
    }
}