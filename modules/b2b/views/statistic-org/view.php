<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\models\consult\Consult;

echo Html::tag('h4', $org_name, ['style'=>'font-weight: 600; margin-bottom: 15px; text-align: center;']);

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        [
            'attribute'=>'employee_id',
            'value'=>function($model) {
                return $model->employee->fullname;
            }
        ],
        [
            'attribute'=>'dep_id',
            'value'=>function($model) {
                return ($model->dep_id) ? $model->department->name : null;
            }
        ],
        [
            'attribute'=>'employee_id',
            'header'=>'Кол-во консультаций',
            'value'=>function ($model) use ($period) {
                return Consult::getConsultCountByParams($model->employee_id, $model->dep_id, $period);
            }
        ],
        [
            'attribute'=>'employee_id',
            'header'=>'Выручка',
            'value'=>function ($model) use ($period) {
                return Consult::getConsultSumByParams($model->employee_id, $model->dep_id, $period) . ' руб.';
            }
        ]
    ]
]);