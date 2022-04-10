<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Рентгенография';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panelHeadingTemplate'=>'<div class="kv-panel-pager">{pager}</div>',
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success', 'style'=>'margin-right: 3px;']) . Html::a('Экспорт', ['export'], ['class'=>'btn btn-info']) . Search::widget(['model'=>$searchModel])
    ],
    'responsive'=>false,
    'columns'=>[
        [
            'attribute'=>'r_n_medk',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 3%;'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 3%;'],
            'header'=>'ID'
        ],
        [
            'attribute'=>'r_fio_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'format'=>'raw',
            'header'=>'ФИО',
            'value'=> function ($model) {
                $patient = $model->patient;
                if ($patient) {
                    $fullname = implode(' ', [$patient->u_fam, $patient->u_ima, $patient->u_otc]);
                    return Html::a($fullname, ['/med/pz-patient/update', 'id'=>$model->r_fio_id], ['class'=>'btn-modal']);
                }

                return null;
            }
        ],
        [
            'attribute'=>'r_obl_issled',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 180px;']
        ],
        [
            'attribute'=>'r_paytype',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 100px;']
        ],
        [
            'attribute'=>'r_data',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 100px;'],
            'header'=>'Дата иссл.',
            'value'=>function($model) {
                return date("d.m.Y", strtotime($model->r_data));
            }
        ],
        [
            'attribute'=>'r_norm_group',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 100px;'],
            'format'=>'raw',
            'value'=>function($model) {
                switch ($model->r_norm_group) {
                    case 0:
                        return Html::tag('span', 'описание', ['class'=>'text-warning']);
                    case 1:
                        return Html::tag('span', 'ок', ['class'=>'text-success']);
                }
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'options'=>['style'=>'width: 100px;'],
            'template'=>'{print} {view} {update} {delete}',
            'buttons'=>[
                'print'=>function($url, $model) {
                    return ($model->r_norm_group !== 0) ? Html::a('<span class="glyphicon glyphicon-print"></span>', $url, ['target'=>'_blank', 'title'=>'Печать']) : null;
                },
                'view'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-eye-open" aria-hidden="true"></span>', $url, ['class'=>'btn-modal', 'title'=>'Просмотр']);
                }
            ]
        ]
    ]
]);