<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Связь с категориями';
$this->params['breadcrumbs'][] = ['label'=>'Симптомы', 'url'=>['/admin/checker-symptoms/index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'symptom_id'=>$symptom_id], ['class'=>'btn btn-success'])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'symptom_id', 
            'group'=>true,
            'groupedRow'=>true,
            'groupOddCssClass'=>'kv-group-even',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return $model->symptom->name;
            }
        ],
        [
            'attribute'=>'bodypart_id',
            'value'=>function ($model) {
                return $model->bodypart->name;
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);