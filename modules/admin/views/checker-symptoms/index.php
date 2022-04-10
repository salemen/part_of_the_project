<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Симптомы и болезни';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
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
        'name',
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>false,
            'template'=>'{checker-relation}',            
            'buttons'=>[
                'checker-relation'=>function ($url, $model) {
                    return Html::a('Связь с категориями', ['checker-relation/index', 'symptom_id'=>$model->id], ['class'=>'btn btn-primary btn-xs', 'title'=>'Связь с частями тела']);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);