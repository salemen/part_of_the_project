<?php
use yii\widgets\DetailView;
use app\models\employee\Employee;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        'fullname',
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
        
        [
            'attribute'=>'phone',
            'value'=>function ($model) {
                return ($model instanceof Employee) ? implode(' / ', [($model->phone) ? : '-', ($model->phone_work) ? : '-']) : $model->phone ;
            }
        ],
        'city',
        [
            'attribute'=>'last_activity',
            'label'=>'Последняя активность',
            'value'=>function ($model) {
                return ($model->last_activity !== null) ? date('d.m.Y H:i', $model->last_activity) : '-';
            }
        ]        
    ]
]);