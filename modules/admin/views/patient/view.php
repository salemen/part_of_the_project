<?php
use yii\widgets\DetailView;

$this->title = $model->fullname;
$this->params['breadcrumbs'][] = ['label'=>'Справочник: Пациенты', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'sex',
            'value'=>function ($model) {
                if ($model->sex === null) { return $model->sex; }
                return ($model->sex) ? 'Мужской' : 'Женский';
            }
        ],
        'username',
        'user_birth',
        'email:email',
        'phone',
        'city',
        'snils',
        [
            'attribute'=>'last_activity',
            'label'=>'Последняя активность',
            'value'=>function ($model) {
                return ($model->last_activity !== null) ? date('d.m.Y H:i', $model->last_activity) : '-';
            }
        ]        
    ]
]);