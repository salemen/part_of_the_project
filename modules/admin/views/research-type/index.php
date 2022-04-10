<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Виды исследований';
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
            'attribute'=>'name_alt',
            'contentOptions'=>['class'=>'kv-align-middle']
        ],
        [
            'attribute'=>'rel_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'value'=>function ($model) {
                return $model->rel ? $model->rel->name : null;
            }
        ],
        [
            'attribute'=>'created_at',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>false,
            'template'=>'{research-index}',            
            'buttons'=>[
                'research-index'=>function ($url, $model) {
                    return Html::a('Показатели', ['research-index/index', 'type_id'=>$model->id], ['class'=>'btn btn-primary btn-xs', 'title'=>'Показатели']);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update}'
        ]
    ]
]);