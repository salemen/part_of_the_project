<?php
use yii\widgets\DetailView;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        'created_at',
        'diagnosis',
        'employee',
        'comment:text'
    ]
]);