<?php
use yii\bootstrap\Alert;
use yii\helpers\Html;
use app\models\monitor\MonitorProtocolOrvi;

function getNextDate($current, $step) {  
    $next = $current + 82800 / $step;
    $nightHours = ['00', '01', '02', '03', '04', '05', '06'];
    
    do {
        $next = $next + 3600;
    } while (in_array(date('H', $next), $nightHours));
    
    return date('d.m.Y H:i', $next);
}

switch ($model->status) {
    case MonitorProtocolOrvi::STATUS_SUCCESS:
        $class = 'alert-success';
        $body = Html::tag('h4', 'Все стабильно. Продолжайте наблюдение.')
            . 'Рекомендуем производить ввод данных один раз в сутки.<br>'
            . 'Дата следующего ввода данных: ' . getNextDate($model->created_at, 1) . ' (время московское)';
        if ($model->p_feel === 'плохое') { $body .= '<br>Пройдите также ' . Html::a('тест на степень утомляемости', ['/express-test']) ;}
        break;
    case MonitorProtocolOrvi::STATUS_WARNING:
        $class = 'alert-warning';
        $body = Html::tag('h4', 'Свяжитесь со специалистом.') 
            . 'Необходима связь со специалистом по телефону или онлайн.<br>Рекомендуем увеличить кратность ввода данных до двух раз в сутки.<br>'
            . 'Дата следующего ввода данных: ' . getNextDate($model->created_at, 2) . ' (время московское)';
        break;
    case MonitorProtocolOrvi::STATUS_DANGER:
        $class = 'alert-danger';
        $body = Html::tag('h4', 'Внимание!')
            . 'Необходим срочный вызов специалиста на дом!<br>Рекомендуем увеличить кратность ввода данных до трех раз в сутки.<br>'
            . 'Дата следующего ввода данных: ' . getNextDate($model->created_at, 3) . ' (время московское)';
        break;
}

echo Alert::widget([
    'body'=>$body,
    'closeButton'=>false,
    'options'=>[
        'class'=>$class
    ]    
]);