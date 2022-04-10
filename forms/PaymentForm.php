<?php
// Форма для раздела "Онлайн-платежи"

namespace app\forms;

use Yii;
use yii\base\Model;

class PaymentForm extends Model
{
    public $code;

    public function rules()
    {
        return [
            [['code'], 'required'],
            [['code'], 'integer'],
            [['code'], 'match', 'pattern'=>'/^[\d]{7}$/i', 'message'=>'Значение «{attribute}» должно содержать 7 цифр.']
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'code'=>'Код заказа'
        ];
    }
}