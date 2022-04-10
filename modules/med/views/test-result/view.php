<?php
use kartik\grid\GridView;

$this->title = 'Тест №' . $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Результаты тестов', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'session_id',
            'group'=>true,
            'groupedRow'=>true,
            'header'=>'Пользователь',
            'value'=>function ($model) {
                if ($model->session->employee) {
                    return $model->session->employee->fullname;
                } elseif ($model->session->patient) {
                    return $model->session->patient->fullname;
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'question_id',
            'value'=>'question.name'
        ],
        [
            'attribute'=>'answer_id',
            'value'=>'answer.name'
        ] 
    ]
]);