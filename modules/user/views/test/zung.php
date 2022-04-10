<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\helpers\AppHelper;

$this->title = 'Результаты теста: Тест Цунга';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Новое тестирование', ['/site/test', 'test_id'=>$test_id], ['class'=>'btn btn-primary'])
    ],
    'responsive'=>false,
    'columns'=>[
        [
            'attribute'=>'result',
            'format'=>'raw',
            'value'=>function ($model) {
                $result = $model->result;
                switch ($result) {
                    case ($result >= 20 && $result < 50):
                        $class = 'text-success';
                        $str = 'Нормальное состояние';
                        break;
                    case ($result >= 50 && $result < 60):
                        $class = 'text-warning';
                        $str = 'Лёгкая депрессия';
                        break;
                    case ($result >= 60 && $result < 70):
                        $class = 'text-warning';
                        $str = 'Умеренная депрессия';
                        break;
                    case ($result >= 70):
                        $class = 'text-danger';
                        $str = 'Тяжелая депрессия';
                        break;
                }

                return AppHelper::declension($result, 'балл', 'балла', 'баллов') . ' - ' . Html::tag('span', $str, ['class'=>$class]);
            }
        ],
        [
            'attribute'=>'date_time',
            'value'=>function ($model) {
                return date('d.m.Y H:i', strtotime($model->date_time)) . ' (МСК)';
            }
        ]                            
    ]
]);