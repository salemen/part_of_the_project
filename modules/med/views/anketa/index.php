<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Анкетирование';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'name',
        'desc',       
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{anketa-result}',
            'buttons'=>[
                'anketa-result'=>function ($url, $model) {
                    return Html::a('Заполненные анкеты', ['/med/anketa-result/index', 'anketa_id'=>$model->id], ['class'=>'btn btn-primary btn-xs', 'title'=>'Заполненные анкеты']);
                }
            ]
        ]
    ]
]);