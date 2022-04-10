<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\modules\covid\models\CovidPages;

$this->title = 'Администрирование: COVID-19 Страницы';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success'])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Статус',
            'template'=>'{toggle-status}',
            'buttons'=>[
                'toggle-status'=>function ($url, $model) {
                    if ($model->status == 10) {
                        return Html::a('<i class="fa fa-check text-success" style="font-size: 20px"></i>', $url);                    
                    } else {
                        return Html::a('<i class="fa fa-times text-danger" style="font-size: 20px"></i>', $url);
                    }
                }
            ]
        ],
        [
            'attribute'=>'controller',
            'value'=>function ($model) {
                $controllers = CovidPages::controllerArray();
                return $controllers[$model->controller];
            }
        ],       
        'name',
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);