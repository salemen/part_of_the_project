<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\data\Organization;
use app\models\consult\Consult;
use app\models\employee\Employee;

$this->title = 'Статистика: Консультации';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,  
    'filterModel'=>$searchModel,
    'export'=>[
        'showConfirmAlert'=>false,
        'target'=>GridView::TARGET_BLANK
    ],
    'exportConfig'=>[
        GridView::EXCEL=>true
    ],
    'panel'=>[
        'heading'=>false
    ],
    'panelBeforeTemplate'=>'{toolbarContainer}{before}<div class="clearfix"></div>',
    'toolbar'=>[
        '{export}',
        '{toggleData}'
    ],
    'responsive'=>false,
    'showPageSummary'=>true,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'created_at',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>[
                    'currentMonth'=>'Текущий месяц',
                    'prevMonth'=>'Прошлый месяц'
                ] + ArrayHelper::map(Consult::find()->orderBy('created_at')->all(),
                    function ($item) { return date('Y', $item->created_at); },
                    function ($item) { return date('Y', $item->created_at); }
                ),
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'header'=>'Дата',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return date('d.m.Y', $model->created_at);
            }
        ],
        [
            'attribute'=>'employee_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Employee::find()->joinWith('advisor')->orderBy('fullname')->all(), 'id', 'fullname'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'value'=>function($model) {
                return ($model->employee) ? $model->employee->fullname : null;
            }
        ],
        [
            'attribute'=>'patient_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filter'=>false,
            'format'=>'raw',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                if ($model->patient) {
                    $fullname = $model->patient->fullname;
                    return ($fullname === 'Аноним') ? $model->patient->phone : $fullname;
                } elseif ($model->employeePatient) {
                    $fullname = $model->employeePatient->fullname;
                    return ($fullname === 'Аноним') ? $model->employeePatient->phone : $fullname;
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'city',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Organization::find()->where(['status'=>10])->orderBy('city')->groupBy('city')->all(), 'city', 'city'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'header'=>'Город',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'value'=>function($model) {
                return ($model->department) ? $model->department->organization->city : null;
            }
        ],
        [
            'attribute'=>'dep_id',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return ($model->dep_id && $model->department) ? $model->department->name : null;
            }
        ],
        [
            'attribute'=>'id',
            'header'=>'Сумма платежа',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'pageSummary'=>function ($summary) { return $summary . ' руб.'; },
            'value'=>function($model) {
                return $model->payment->orderSumAmount;
            }
        ],
        [
            'attribute'=>'id',
            'header'=>'Доход',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],            
            'mergeHeader'=>true,
            'pageSummary'=>function ($summary) { return $summary . ' руб.'; },
            'value'=>function($model) {
                return $model->payment->shopSumAmount;
            }
        ],
        [
            'attribute'=>'id',
            'header'=>'Статус',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                if ($model->is_canceled) {
                    return 'Отменена';
                } else {
                    return ($model->is_end) ? 'Завершена' : 'Не завершена';
                }
            }
        ]
    ]
]);