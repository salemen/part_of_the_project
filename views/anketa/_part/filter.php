<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;

$isSearch = Yii::$app->request->get('search');
 
$form = ActiveForm::begin([
    'action'=>['index'],
    'id'=>'filter-form',    
    'method'=>'get'
]);

echo $form->field($searchModel, 'search', ['options'=>['id'=>'filter-name']])
    ->textInput(['placeholder'=>'Поиск...', 'value'=>Yii::$app->request->get('search')])
    ->error(false)->label('Поиск анкеты') . '<hr>';
echo Html::submitButton('Найти', ['class'=>'btn btn-primary pull-right', 'style'=>'margin-right: 3px;']);
if ($isSearch) { echo Html::a('Очистить фильтр', ['index'], ['class'=>'btn btn-danger btn-reset']); }

$form->end();