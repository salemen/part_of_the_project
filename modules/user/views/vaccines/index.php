<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Мои данные о вакцинации';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id'=>'user-vaccines',
    'dataProvider'=>$dataProvider,
    'responsive'=>false,
    'panel'=>[
        'before'=>Html::a('Добавить данные о вакцинации', ['create'], ['class'=>'btn btn-primary btn-modal'])
    ],
    'columns'=>[
        'vaccine',
        'employee',
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