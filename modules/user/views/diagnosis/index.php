<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Мои диагнозы';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить диагноз', ['create'], ['class'=>'btn btn-primary btn-modal', 'style'=>'margin-right: 3px;']) . Html::a('Статистика заболеваний', ['chart'], ['class'=>'btn btn-info'])
    ],
    'responsive'=>false,
    'columns'=>[
        [
            'attribute'=>'diagnosis',
            'value'=>function ($model) {
                return $model->diagnosis;
            }
        ],
        [
            'attribute'=>'employee'
        ],
        'created_at',
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view} {update} {delete}',
            'buttons'=>[
                'view'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class'=>'btn-modal', 'title'=>'Просмотр']);
                },
                'update'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-pencil"></span>', $url, ['class'=>'btn-modal', 'title'=>'Изменить']);
                }
            ]
        ]
    ]
]);