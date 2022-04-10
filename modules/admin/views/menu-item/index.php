<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Пункты меню';
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
            'attribute'=>'section_id', 
            'group'=>true,
            'groupedRow'=>true,
            'groupOddCssClass'=>'kv-group-even',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return $model->section->name;
            }
        ],
        [
            'attribute'=>'name',
            'contentOptions'=>['class'=>'kv-align-middle']
        ],
        [
            'attribute'=>'url',
            'contentOptions'=>['class'=>'kv-align-middle']
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);