<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Организации';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
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
        'city',
        'inn',
        'kpp',
        'ogrn',
        [
            'attribute'=>'is_santal',
            'value'=>function ($model) {
                return ($model->is_santal) ? 'Да' : 'Нет';
            }
        ],     
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view}'
        ]
    ]
]) ?>