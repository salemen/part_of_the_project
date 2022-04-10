<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Консультанты';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>$user->identity->roles->is_santal ? null : Html::a('Добавить', ['create'], ['class'=>'btn btn-success'])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'status', 
            'format'=>'raw',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                if ($model->status == 10) { $color = 'green'; $text = 'Активен'; } else { $color = 'orangered'; $text = 'Неактивен'; }
                return Html::tag('span', $text, ['style'=>['color'=>$color]]);
            }
        ],
        [
            'attribute'=>'employee_id',
            'value'=>function ($model) {
                $name = ($model->employee) ? $model->employee->fullname : '-';
                return $name;
            }
        ],
        [
            'attribute'=>'cost',
            'mergeHeader'=>true,
            'header'=>'Стоимость первичная / вторичная',
            'value'=>function ($model) {
                return $model->cost . ' / ' . (($model->cost_2nd !== null) ? $model->cost_2nd : '-');
            }            
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}',
            'visible'=>!$user->identity->roles->is_santal
        ]
    ]
]) ?>