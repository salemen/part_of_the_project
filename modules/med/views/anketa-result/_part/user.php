<?php
use yii\helpers\Html;
use app\helpers\AppHelper;

echo Html::beginTag('div', ['class'=>'table-responsive']);
    echo Html::beginTag('table', ['class'=>'table table-bordered']);
        echo Html::beginTag('tr', ['style'=>'text-align:center;']);
            echo Html::tag('td', $anketa->name . '</br>' . $anketa->desc);
        echo Html::endTag('tr');
    echo Html::endTag('table');
echo Html::endTag('div');

echo Html::beginTag('div', ['class'=>'table-responsive']);
    echo Html::beginTag('table', ['class'=>'table table-bordered']);
        echo Html::beginTag('tr');
            echo Html::tag('td', 'Дата анкетирования (день, месяц, год)');
            echo Html::tag('td', date('d.m.Y', $date));
        echo Html::endTag('tr');
        echo Html::beginTag('tr');
            echo Html::tag('td', 'Ф.И.О. пациента: ' . $user->fullname);
            echo Html::tag('td', 'Пол: ' . (($user->sex !== null) ? (($user->sex) ? 'Муж.' : 'Жен.') : ''));
        echo Html::endTag('tr');
        echo Html::beginTag('tr');
            echo Html::tag('td', 'Дата рождения: ' . $user->user_birth);
           echo Html::tag('td', 'Полных лет: ' . AppHelper::calculateAge($user->user_birth));
        echo Html::endTag('tr');
        echo Html::beginTag('tr');
            echo Html::tag('td', 'Медицинская организация:', ['colspan'=>2]);
        echo Html::endTag('tr');
        echo Html::beginTag('tr');
            echo Html::tag('td', 'Должность и Ф.И.О. проводящего анкетирование и подготовку заключения по его<br> результатам:', ['colspan'=>2]);
        echo Html::endTag('tr');
    echo Html::endTag('table');
echo Html::endTag('div');