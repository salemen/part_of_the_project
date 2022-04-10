<?php
namespace app\modules\user\forms;

use Yii;
use yii\base\Model;
use app\models\patient\Patient;

class PassChangeForm extends Model
{
    public $password;
    public $newPassword;
    
    private $_user;
    
    public function __construct(Patient $user, $config = [])
    {
        $this->_user = $user;
        
        parent::__construct($config);
    }
    
    public function rules()
    {
        return [
            [['password', 'newPassword'], 'required'],
            ['password', 'validatePassword'],
            ['newPassword', 'string', 'min'=>6]
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'password'=>'Текущий пароль',
            'newPassword'=>'Новый пароль'
        ];
    }
    
    public function changePassword()
    {
        if ($this->validate()) {
            $user = $this->_user;
            $user->setPassword($this->newPassword);
            return $user->save();
        } else {
            return false;
        }
    }
    
    public function validatePassword($attribute)
    {
        if (!$this->hasErrors()) {
            if (!$this->_user->validatePassword($this->$attribute)) {
                $this->addError($attribute, Yii::t('app', 'Текущий пароль введен неверно!'));
            }
        }
    }
}