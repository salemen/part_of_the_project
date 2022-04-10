<?php
use yii\widgets\DetailView;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        'created_at',
        'vaccine',
        'employee',
        'comment:text'
    ]
]);