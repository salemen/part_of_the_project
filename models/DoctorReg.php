<?php

namespace app\models;

use borales\extensions\phoneInput\PhoneInputValidator;
use yii\base\Model;
use Yii;


class DoctorReg extends \yii\db\ActiveRecord
{
      //public $phone;

    public static function tableName()
    {
        return 'doctor_reg';
    }

   
    public function rules()
    {
       
        return [
            [['phone'], 'required'],
            [['phone'], PhoneInputValidator::className()],
            [['phone'], 'validatePhone'],
            [['email'], 'validateEmail'],
            [['date'], 'safe'],
            [['username', 'surname', 'patronymic', 'email'], 'string', 'max' => 50],
            [['phone'], 'string', 'max' => 20],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '№',
            'username' => 'Имя',
            'surname' => 'Фамилия',
            'patronymic' => 'Отчество',
            'email' => 'Email',
            'phone' => 'Номер телефона',
            'date' => 'Дата регистрации',
        ];
    }


    public function validatePhone($attribute)
    {
        if ($this->phone) {
            if (!$this->hasErrors()) {
                $exists = false;

                if ($exists === false) {
                    $exists = doctorreg::find()->where(['phone'=>$this->phone])->exists();
                }

                if ($exists) {
                    $this->addError($attribute, 'Пользователь с таким номером телефона уже зарегистрирован.');
                }
            }
        }
    }

    public function validateEmail($attribute)
    {
        if (!$this->hasErrors()) {
            $exists = false;

            if ($exists === false) {
                $exists = doctorreg::find()->where(['email'=>$this->email])->exists();
            }
            
            if ($exists) {
                $this->addError($attribute, 'Пользователь с таким E-mail уже зарегистрирован.');
            }
        }
    }

}
