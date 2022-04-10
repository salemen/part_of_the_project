<?php
use yii\widgets\DetailView;

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label'=>'Организации', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        'name',
        'city',
        'inn',        
        'kpp',
        'ogrn',
        'address'
    ]
]); 