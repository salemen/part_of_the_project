<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Мои расшифровки результатов анализов';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'responsive'=>false,
    'columns'=>[
        [
            'attribute'=>'index_id',
            'value'=>'researchIndex.name'
        ],
        [
            'attribute'=>'unit_id',
            'value'=>'researchUnit.name'
        ],
        'value',
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view} {delete}',
            'buttons'=>[
                'view'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', $url, ['class'=>'btn-modal', 'title'=>'Просмотр']);
                }
            ]
        ]
    ]
]);