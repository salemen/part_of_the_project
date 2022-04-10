<?php
use app\models\monitor\MonitorPassport;

$model = MonitorPassport::find()->joinWith(['employee', 'patient']);
$all = $model->where(['is_end'=>false])->count();

function getAddCount($model, $city = null) {
    return $model->where(['is_end'=>false])
        ->andWhere(['OR', ['employee.city'=>$city], ['patient.city'=>$city]])
        ->andWhere(['>', 'monitor_passport.created_at', mktime(0, 0, 0)])
        ->count();
}

function getLeaveCount($model, $city = null) {
    return $model->where(['is_end'=>true])
        ->andWhere(['OR', ['employee.city'=>$city], ['patient.city'=>$city]])
        ->andWhere(['>', 'monitor_passport.updated_at', mktime(0, 0, 0)])
        ->count();
}

echo 'Текущая дата: ' . date('d.m.Y') . ' (МСК)<br>';
echo 'Всего: ' . $all . '<br>';
echo 'Сегодня поступило / снято: ' . getAddCount($model) . ' / ' . getLeaveCount($model) . '<br>';
echo 'Томск: ' . getAddCount($model, 'Томск') . ' / ' . getLeaveCount($model, 'Томск') . '<br>';
echo 'Новосибирск: ' . getAddCount($model, 'Новосибирск') . ' / ' . getLeaveCount($model, 'Новосибирск') . '<br>';
echo 'Краснодар: ' . getAddCount($model, 'Краснодар') . ' / ' . getLeaveCount($model, 'Краснодар') . '<br>';
echo 'Геленджик: ' . getAddCount($model, 'Геленджик') . ' / ' . getLeaveCount($model, 'Геленджик') . '<br>';
echo 'Кызыл: ' . getAddCount($model, 'Кызыл') . ' / ' . getLeaveCount($model, 'Кызыл') . '<br>';