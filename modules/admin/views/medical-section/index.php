<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Направления';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success'])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Статус',
            'template'=>'{toggle-status}',
            'buttons'=>[
                'toggle-status'=>function ($url, $model) {
                    $class = ($model->status) ? 'fa fa-check text-success' : 'fa fa-times text-danger';                    
                    return Html::a("<i class='{$class}' style='font-size: 20px;'></i>", $url);
                }
            ]
        ],
        [
            'attribute'=>'name',
            'contentOptions'=>['class'=>'kv-align-middle']
        ],        
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update}'
        ]
    ]
]);