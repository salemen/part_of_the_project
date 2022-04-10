<?php
use kartik\grid\GridView;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'vac_id',
            'label'=>'Вакцина',
            'value'=>function($model) {
                return $model->vaccine->name;
            }
        ]
    ]
]);