<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Специальные регалии сотрудника';
$this->params['breadcrumbs'][] = ['label'=>'Справочник: Сотрудники', 'url'=>['/admin/employee/index']];
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'employee_id'=>$employee_id], ['class'=>'btn btn-success']),
        'heading'=>false
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'employee_id', 
            'group'=>true,
            'groupedRow'=>true,
            'groupOddCssClass'=>'kv-group-even',
            'mergeHeader'=>true,
            'value'=>function ($model) {
                return ($model->employee) ? $model->employee->fullname : null;
            }
        ],
        [
            'attribute'=>'type',
            'value'=>function ($model) {
                switch ($model->type) {
                    case 10:
                        return 'Должность';
                }
            }
        ],        
        'value',
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]);