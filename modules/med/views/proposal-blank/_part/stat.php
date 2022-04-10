<?php

use app\models\proposal\ProposalCallDoctor;

$model = ProposalCallDoctor::find();
$all = $model->count();


function getAddCount($model, $city = null) {
    return $model->where(['city'=>$city])
        ->andWhere(['>', 'created_at', mktime(0, 0, 0)])
        ->count();
}
function getAddAll($model) {
    return $model->andWhere(['>', 'created_at', mktime(0, 0, 0)])
        ->count();
}



echo 'Текущая дата: ' . date('d.m.Y') . ' (МСК)<br>';
echo 'Всего: ' . $all . '<br>';
echo 'Сегодня поступило: ' . getAddAll($model) .'<br>';
echo 'Томск: ' . getAddCount($model, 'Томск') . '<br>';
echo 'Новосибирск: ' . getAddCount($model, 'Новосибирск') . '<br>';
echo 'Краснодар: ' . getAddCount($model, 'Краснодар') . '<br>';
echo 'Геленджик: ' . getAddCount($model, 'Геленджик')  . '<br>';
echo 'Кызыл: ' . getAddCount($model, 'Кызыл') . '<br>';