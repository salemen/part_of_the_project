<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\employee\Employee;
use app\models\payments\Payments;
use app\models\consult\search\Consult;


$this->title = 'Менеджер: Консультации';
$this->params['breadcrumbs'][] = $this->title;

function showBtnGroup($model) {
    $content = Html::button('Действия', ['class'=>'btn btn-success btn-xs dropdown-toggle', 'data'=>['toggle'=>'dropdown']]);
    $items = '';
    $links = [
        $model->is_end ? null : Html::a('Изменить консультанта', ['change-employee', 'id'=>$model->id], ['class'=>'btn-modal']),
        Html::a($model->is_end ? 'Открыть консультацию' : 'Закрыть консультацию', ['change-end', 'id'=>$model->id]),
        Html::a($model->is_canceled ? 'Просмотр причины отмены консультации' : 'Отменить консультацию', ['cancel', 'id'=>$model->id], ['class'=>'btn-modal']),
        Html::a('Просмотр переписки', ['read-history', 'id'=>$model->id], ['class'=>'btn-modal'])
    ];

    if ($links) {
        foreach ($links as $link) {
            $items .= Html::tag('li', $link);
        }
    }

    $content .= Html::tag('ul', $items, ['class'=>'dropdown-menu pull-right']);

    return Html::tag('div', $content, ['class'=>'btn-group']);
}

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
    'pjax'=>true,
    'responsive'=>false,
    'rowOptions'=>function($model){
        if ($model->is_canceled) {
            return ['class'=>'danger'];
        }
    },
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'created_at',
            'filterType'=>GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions'=>[
                'convertFormat'=>true,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['locale'=>['format'=>'d.m.Y']],
            ],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return date('d.m.Y H:i', $model->created_at);
            }
        ],

        [
            'attribute'=>'is_special',
            'header'=>'Наименование услуги',
//            'value'=>function($model) {if ($dataProvider[is_special] == 1 ){
//                return "Специальный";
//            }},

//            'value' => function($data) use ($card) {
//                if (!empty($card)){
//                    return "Расшифровка ЭКГ";
//                }else return "Онлайн консультация";
//            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Consult::find()->orderBy(['id'=>SORT_DESC])->all(), 'employee_id', 'is_special'),
                //'data'=>ArrayHelper::map(Employee::find()->joinWith('advisor')->orderBy('fullname')->all(), 'id', 'fullname'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true],

            ],

        ],

        [
            'attribute'=>'ended_at',
            'filterType'=>GridView::FILTER_DATE_RANGE,
            'filterWidgetOptions'=>[
                'convertFormat'=>true,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['locale'=>['format'=>'d.m.Y']],
            ],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return ($model->is_end) ? (($model->ended_at === null) ? null : date('d.m.Y H:i', $model->ended_at)) : 'Не завершена';
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
            'attribute'=>'dep_id',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return ($model->dep_id) ? $model->department->name : null;
            }
        ],
        [
            'attribute'=>'id',
            'header'=>'Сумма платежа',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return $model->payment->orderSumAmount;
            }
        ],
        [
            'attribute'=>'id',
            'header'=>'Доход',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return $model->payment->shopSumAmount;
            }
        ],
        [
            'attribute'=>'city',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Employee::find()->groupBy('city')->all(), 'city', 'city'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'header'=>'Город',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'value'=>function($model) {
                return ($model->employee) ? $model->employee->city : null;
            }
        ],
        [
            'attribute'=>'patient_id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(
                    Payments::find()
                        ->joinWith('patient')
                        ->distinct('customerNumber')
                        ->where(['orderType'=>Payments::TYPE_CONSULT])
                        ->orderBy('fullname')
                        ->all(), 'customerNumber', function($item) {
                    if ($item->patient) {
                        $fullname = $item->patient->fullname;
                        return ($fullname === 'Аноним') ? $item->patient->phone : $fullname;
                    } elseif ($item->employeePatient) {
                        $fullname = $item->employeePatient->fullname;
                        return ($fullname === 'Аноним') ? $item->employeePatient->phone : $fullname;
                    } else {
                        return null;
                    }
                }),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'format'=>'raw',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'value'=>function($model) {
                if ($model->patient) {
                    $fullname = $model->patient->fullname;
                    $result = ($fullname === 'Аноним') ? $model->patient->phone : $fullname;
                    return Html::a($result, ['/admin/patient/view', 'id'=>$model->patient_id]);
                } elseif ($model->employeePatient) {
                    $fullname = $model->employeePatient->fullname;
                    $result = ($fullname === 'Аноним') ? $model->employeePatient->phone : $fullname;
                    return Html::a($result, ['/admin/patient/view', 'id'=>$model->patient_id]);
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'header'=>'Тест',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'60px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return ($model->payment->isTest) ? 'Да' : 'Нет';
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{actions}',
            'buttons'=>[
                'actions'=>function($url, $model) {
                    return showBtnGroup($model);
                }
            ]
        ]
    ]
]);