<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$form = ActiveForm::begin(['id'=>'test-email-form']);
$phoneLabel = 'E-mail';

echo $form->field($model, 'email')->textInput(['maxlenght'=>true, 'placeholder'=>'Введите E-mail'])->error(false)->label(false);

echo Html::submitButton('Тест', ['class'=>'btn btn-primary']);

$form->end();