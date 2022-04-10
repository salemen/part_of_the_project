<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\employee\Employee;
use app\models\payments\Payments;
use app\models\cardio\Cardio;
use yii\web\JsExpression;
use app\models\consult\search\Consult;
use kartik\daterange\DateRangePicker;


$this->title = 'Подробнее о сотруднике';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="employee-degree-index">


    <!-------Живой поиск--------->

    <form method="get" id="search" action="<?= Url::to(['static-one/search']) ?>">
        <?php $form = ActiveForm::begin(); ?>

        <div class="col-md-6">
           <?= Html::tag('label', 'Сотрудник', ['class' => 'control-label']);?>
        <?php


        $model_cat = Employee::find()->all();
        foreach ($model_cat as $key => $item) {
            $id = $key;
            $model3 = $item;
        }

        $url = Url::toRoute(['/cart/dok-list']);


        echo $form->field($model3, 'fullname', ['template' => "{label}\n{input}"], '<input type="submit" value="">')->widget(Select2::classname(), [
            'name' => 'kv-state-230',
            //'addon'=>$addon,
            'options' => ['multiple'=>true, 'placeholder' => 'Поиск сотрудника..'],
            'pluginOptions' => [
                'allowClear' => true,
                'minimumInputLength' => 2,
                'ajax' => [
                    'url' => $url,
                    'type' =>"GET",
                    'type' =>"GET",
                    //'contentType' => 'application/json; charset=utf-8',
                    'dataType' => 'json',
                    'data' => new JsExpression('function(params) { return {q:params.term}; }'),
                ],

                'escapeMarkup' => new JsExpression('function (markup) { return markup; }'),
                'templateResult' => new JsExpression('function(name) { return name.text; }'),
                'templateSelection' => new JsExpression('function (name) { return name.text; }'),
            ],
        ])->label(false);?>
    </div>
    <!------------------- календарь в живом поиске---------------->

        <div class="col-md-6">
            <?= Html::tag('label', 'Период', ['class'=>'control-label']) ?>
            <?= DateRangePicker::widget([
                'id'=>'period_picker',
                'name'=>'picker_period',
                //'value'=>$periodValue,
                'options'=>['class'=>'form-control', 'placeholder'=>'Период'],
                'pluginEvents'=>[
                    'apply.daterangepicker'=>'function(e, p) {
                        var start = p.startDate.format("X");
                        var end = p.endDate.format("X");                    
                        var value = start + "-" + end;

                        insertParam("period", value);
                    }',
                    'cancel.daterangepicker'=>'function(e, p) {
                        insertParam("period", "");
                    }'
                ],
                'pluginOptions'=>[
                    'locale'=>[
                            'locale'=>['format'=>'Y-m-d h:i:s'],
                        'cancelLabel'=>'Очистить'
                    ]
                ]
            ]) ?>
        </div>

        <div class="col-md-12">
        <center> <input type="submit" class="btn btn-primary" name="search" value="Поиск сотрудника">
            <input type="submit" class="btn btn-primary" name="search" value="Сбросить поиск">
        </center>
        </div>
        <?php ActiveForm::end();?>


    </form>

    <!---------------Конец живого поиска-------------------->
<?php
//$data=
//Payments::find()
//->joinWith('patient')
//->orderBy('fullname')
//->one();
//
//echo '<pre>';
//var_dump([$data]);
//echo '</pre>';
//?>

</div>
<?php
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

//        [
//            'attribute'=>'ended_at',
//            'filterType'=>GridView::FILTER_DATE_RANGE,
//            'filterWidgetOptions'=>[
//                'convertFormat'=>true,
//                'options'=>['placeholder'=>'Фильтр'],
//                'pluginOptions'=>['locale'=>['format'=>'d.m.Y']],
//            ],
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
//            'value'=>function($model) {
//                return ($model->is_end) ? (($model->ended_at === null) ? null : date('d.m.Y H:i', $model->ended_at)) : 'Не завершена';
//            }
//        ],
        [
            'attribute'=>'is_special',
            'header'=>'Наименование услуги',
//            'value'=>function($model) {if ($dataProvider[is_special] == 1 ){
//                return "Специальный";
//            }},

         'value' => function($data) use ($card) {
                 if (!empty($card)){
                     return "Расшифровка ЭКГ";
                 }else return "Онлайн консультация";
                },
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
            'value' => function($data, $model) use ($card) {
                if (!empty($card)){
                    $name1 = $card[0]['employee_id'];
                    $name = Employee::find()->select('fullname')->where(['id' => $name1])->one();
                    return $name->fullname;
                } else {

                    return "нет";}

                },
                'value'=>function($model) {
                return ($model->employee) ? $model->employee->fullname : null;
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
//        [
//            'attribute'=>'dep_id',
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
//            'mergeHeader'=>true,
//            'value'=>function($model) {
//                return ($model->dep_id) ? $model->department->name : null;
//            }
//        ],
        [
            'attribute'=>'id',
            'header'=>'Стоимость',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'100px'],
            'value'=>function($model) {
                return $model->payment->orderSumAmount;
            }
        ],
        [
            'attribute'=>'created',
            'header'=>'Дата',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return date('d.m.Y H:i', $model->created_at);
            }
        ],
        [
            'attribute'=>'id',
            'header'=>'Доход',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            //'mergeHeader'=>true,
            'value'=>function($model) {
                return $model->payment->shopSumAmount;
            }
        ],
        [
            'attribute'=>'',
            'header'=>'Статус',
           'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],

        ],

//        [
//            'attribute'=>'patient_id',
//            'contentOptions'=>['class'=>'kv-align-middle'],
//            'filterType'=>GridView::FILTER_SELECT2,
//            'filterWidgetOptions'=>[
//                'data'=>ArrayHelper::map(
//                    Payments::find()
//                        ->joinWith('patient')
//                        ->distinct('customerNumber')
//                        ->where(['orderType'=>Payments::TYPE_CONSULT])
//                        ->orderBy('fullname')
//                        ->all(), 'customerNumber', function($item) {
//                    if ($item->patient) {
//                        $fullname = $item->patient->fullname;
//                        return ($fullname === 'Аноним') ? $item->patient->phone : $fullname;
//                    } elseif ($item->employeePatient) {
//                        $fullname = $item->employeePatient->fullname;
//                        return ($fullname === 'Аноним') ? $item->employeePatient->phone : $fullname;
//                    } else {
//                        return null;
//                    }
//                }),
//                'hideSearch'=>false,
//                'options'=>['placeholder'=>'Фильтр'],
//                'pluginOptions'=>['allowClear'=>true]
//            ],
//            'format'=>'raw',
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
//            'value'=>function($model) {
//                if ($model->patient) {
//                    $fullname = $model->patient->fullname;
//                    $result = ($fullname === 'Аноним') ? $model->patient->phone : $fullname;
//                    return Html::a($result, ['/admin/patient/view', 'id'=>$model->patient_id]);
//                } elseif ($model->employeePatient) {
//                    $fullname = $model->employeePatient->fullname;
//                    $result = ($fullname === 'Аноним') ? $model->employeePatient->phone : $fullname;
//                    return Html::a($result, ['/admin/patient/view', 'id'=>$model->patient_id]);
//                } else {
//                    return null;
//                }
//            }
//        ],
//        [
//            'attribute'=>'id',
//            'contentOptions'=>['class'=>'kv-align-middle'],
//            'header'=>'Тест',
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'60px'],
//            'mergeHeader'=>true,
//            'value'=>function($model) {
//                return ($model->payment->isTest) ? 'Да' : 'Нет';
//            }
//        ],
       // [
//            'class'=>'kartik\grid\ActionColumn',
//            'template'=>'{actions}',
//            'buttons'=>[
//                'actions'=>function($url, $model) {
//                    return showBtnGroup($model);
//                }
//            ]
        //]
    ]
]); ?>
<!--Вернуться назад-->
 <table class="table table-hover table-striped">
       <tr align="center">
		<td align="center"><input class="button" type="button" value="Вернуться назад" onclick="javascript:history.go(-1)" /></td>
	</tr>
   </table>
</div>