<?php
use yii\widgets\DetailView;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'attribute'=>'index_id',
            'value'=>function ($model) {
                return $model->researchIndex->name;
            }
        ],
        [
            'attribute'=>'unit_id',
            'value'=>function ($model) {
                return $model->researchUnit->name;
            }
        ],
        'value'
    ]
]);