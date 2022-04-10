<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Администрирование: Слайдер';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success', 'style'=>'margin-right: 3px;']) . Html::a('Установить порядок', ['sort'], ['class'=>'btn btn-info'])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],  
        [
            'attribute'=>'file',
            'format'=>'raw',
            'value'=>function ($model) {
                return Html::img('/uploads/' . $model->file, ['class'=>'img-responsive', 'style'=>'width: 200px;']);
            }
        ],
        [
            'attribute'=>'name',
            'contentOptions'=>['class'=>'kv-align-middle']
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
            'template'=>'{update} {delete}'
        ]
    ]
]);