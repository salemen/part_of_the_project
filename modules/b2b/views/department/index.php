<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Подразделения';
$this->params['breadcrumbs'][] = ['label'=>'Организации', 'url'=>['/b2b/organization/index']];
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>$user->identity->roles->is_santal ? null : Html::a('Добавить', ['create', 'org_id'=>$org->id], ['class'=>'btn btn-success'])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],     
        [
            'attribute'=>'status', 
            'format'=>'raw',
            'value'=>function ($model) {
                if ($model->status == 10) { $color = 'green'; $text = 'Активен'; } else { $color = 'orangered'; $text = 'Неактивен'; }
                return Html::tag('span', $text, ['style'=>['color'=>$color]]);
            }
        ],
        'name',
        'address',        
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>$user->identity->roles->is_santal ? '' : '{update}'
        ]
    ]
]) ?>