<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\bootstrap\Alert;

$this->title = 'Бланки';
$this->params['breadcrumbs'][] = $this->title; ?>

<?= Alert::widget([
    'body' => $this->render('_part/stat'),
    'closeButton' => false,
    'options' => [
        'class' => 'alert-default'
    ]
]) ?>

<?php
echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'panel'=>[
        'before'=>$this->render('_search', ['model'=>$searchModel]),
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'user_f',
        'user_i',
        'user_o',
        'city',
        'phone',
        'visit_date',
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view} {update}'
        ]
    ]
]);