<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Администрирование: COVID-19 Карты';
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
        'name',
        [
            'attribute'=>'covid_hospital',
            'contentOptions'=>['class'=>'kv-align-middle kv-align-center', 'style'=>'width: 100px;'],
            'headerOptions'=>['class'=>'kv-align-middle kv-align-center', 'style'=>'width: 100px;'],
            'format'=>'raw',
            'value'=>function ($model) {
                return ($model->covid_hospital) ? '<i class="fa fa-check text-success" style="font-size: 20px"></i>' : '<i class="fa fa-times text-danger" style="font-size: 20px"></i>';                    
            }
        ],
        [
            'attribute'=>'covid_test',
            'contentOptions'=>['class'=>'kv-align-middle kv-align-center', 'style'=>'width: 100px;'],
            'headerOptions'=>['class'=>'kv-align-middle kv-align-center', 'style'=>'width: 100px;'],
            'format'=>'raw',
            'value'=>function ($model) {
                return ($model->covid_test) ? '<i class="fa fa-check text-success" style="font-size: 20px"></i>' : '<i class="fa fa-times text-danger" style="font-size: 20px"></i>';                    
            }
        ],
        [
            'attribute'=>'covid_vaccine',
            'contentOptions'=>['class'=>'kv-align-middle kv-align-center', 'style'=>'width: 100px;'],
            'headerOptions'=>['class'=>'kv-align-middle kv-align-center', 'style'=>'width: 100px;'],
            'format'=>'raw',
            'value'=>function ($model) {
                return ($model->covid_vaccine) ? '<i class="fa fa-check text-success" style="font-size: 20px"></i>' : '<i class="fa fa-times text-danger" style="font-size: 20px"></i>';                    
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);