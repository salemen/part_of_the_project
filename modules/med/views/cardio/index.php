<?php
use kartik\grid\GridView;
use app\models\employee\Employee;

$this->title = 'Расшифровка ЭКГ';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'patient_id',
            'value'=>function ($model) {
                if ($model->patient == null) {
                    return Employee::findOne($model->patient_id)->fullname;
                }
                
                return $model->patient->fullname;
            }
        ],
        [
            'attribute'=>'employee_id',
            'label'=>'Исполнитель',
            'value'=>function ($model) {
                if ($model->employee_id == null) {
                    return 'Исполнитель не назначен';
                } else {
                    return $model->employee->fullname;
                } 
            }
        ],
        [
            'attribute'=>'is_end',
            'format'=>'raw',
            'label'=>'Статус заявки',
            'value'=>function ($model) {
                if ($model->employee_id == null && $model->is_end == false) {
                    return '<span class="text-danger">Ожидает исполнителя</span>';
                } elseif ($model->employee_id !== null && $model->is_end == false) {
                    return '<span class="text-warning">В работе</span>';
                } elseif ($model->is_end) {
                    return '<span class="text-success">Выполнена</span>';
                }
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view}'
        ]
    ]
]);