<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Заполненные анкеты';
$this->params['breadcrumbs'][] = ['label'=>'Анкетирование', 'url'=>['/med/anketa/index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id'=>'anketa-sessions',
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Search::widget(['model'=>$searchModel, 'action'=>['index', 'anketa_id'=>$anketa_id]])
    ],
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],    
        [
            'attribute'=>'patient_id',
            'label'=>'Пациент',
            'value'=>function ($model) {
                if ($model->employee) {
                    return $model->employee->fullname;
                } elseif ($model->patient) {
                    return $model->patient->fullname;
                } else {
                    return '-';
                }

            }
        ],
        [
            'attribute'=>'anketa_id',
            'label'=>'Название',
            'value'=>function ($model) {
                return $model->anketa->name;
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'attribute'=>'is_end',
            'label'=>'Статус',
            'value'=>function ($model) {
                return ($model->is_end) ? 'Анкета заполнена полностью' : 'Анкета не заполнена';
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view}',
            'buttons'=>[
                'view'=>function($url, $model) {
                    return Html::a('Результаты', $url, ['class'=>($model->is_end) ? 'btn btn-xs btn-primary' : 'btn btn-xs btn-danger']);                                      
                }
            ]
        ]
    ]
]);