<?php
use yii\helpers\Html;

$input = null;

switch ($type) {
    case 'age':
        $input = Html::activeInput('number', $model, 'value', ['class'=>'form-control', 'min'=>0, 'max'=>100]);
        break;
    case 'sex':
        $input = Html::activeDropDownList($model, 'value', [1=>'Мужчина', 0=>'Женщина'], ['class'=>'form-control', 'prompt'=>'']);
        break;
    default: 
        $input = Html::activeInput('text', $model, 'value', ['class'=>'form-control']);
}

echo $input;