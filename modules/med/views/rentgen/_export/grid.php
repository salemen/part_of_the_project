<?php
use kartik\grid\GridView;
use yii\helpers\Html;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'r_fio_id',
            'header'=>'ФИО',
            'value'=> function ($model) {
                $patient = $model->patient;                
                return ($patient) ? implode(' ', [$patient->u_fam, $patient->u_ima, $patient->u_otc]) : null;
            }
        ],
        [
            'attribute'=>'r_fio_id',
            'header'=>'Дата рождения',
            'value'=> function ($model) {
                $patient = $model->patient;                
                return ($patient) ? date('d.m.Y', strtotime($patient->u_data_ros)) : null;
            }
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
        'r_obl_issled',
        [
            'attribute'=>'r_norm_group',
            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 100px;'],
            'format'=>'raw',
            'value'=>function($model) {
                switch ($model->r_norm_group) {
                    case 0:
                        return Html::tag('span', 'описание', ['class'=>'text-warning']);
                    case 1:
                        return Html::tag('span', 'норма', ['class'=>'text-success']);
                    case 2:
                        return Html::tag('span', 'патология', ['class'=>'text-danger']);
                }
            }
        ],
        'r_sakl_vrach'
    ]
]);