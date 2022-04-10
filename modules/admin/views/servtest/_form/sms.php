<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$form = ActiveForm::begin(['id'=>'test-sms-form']);

echo $form->field($model, 'phone')->widget(MaskedInput::className(), [
    'mask'=>'79999999999',
    'options'=>[
        'class'=>'form-control',
        'placeholder'=>'Введите номер телефона'
    ]
])->error(false)->label(false);

echo Html::submitButton('Тест', ['class'=>'btn btn-primary']);

$form->end();