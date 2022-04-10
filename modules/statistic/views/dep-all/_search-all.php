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
use app\modules\b2b\models\ConsultType;
use app\models\data\Department;
use app\models\employee\EmployeePosition;



$this->params['breadcrumbs'][] = $this->title;
?>


<div class="employee-degree-index">


    <!-----Живой поиск------->

    <form method="get" id="search" action="<?= Url::to(['dep-all/index']) ?>">
        <?php $form = ActiveForm::begin(); ?>

        <div class="col-md-6" style="padding-right: 5px;padding-left: 15px;padding-top: 10px">
            <?= Html::tag('label', 'Организация', ['class' => 'control-label']);?>
            <?php


            $model_cat = Employee::find()->all();
            foreach ($model_cat as $key => $item) {
                $id = $key;
                $model3 = $item;
            }

            $url = Url::toRoute(['/cart/dok-dep-all']);


            echo $form->field($model3, 'fullname', ['template' => "{label}\n{input}"], '<input type="submit" value="">')->widget(Select2::classname(), [
                'name' => 'kv-state-230',
                //'addon'=>$addon,
                'options' => ['multiple'=>true, 'placeholder' => 'Поиск организации..'],
                'pluginOptions' => [
                    'allowClear' => true,
                    'minimumInputLength' => 1,
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

        <div class="col-md-6" style="padding-right: 15px;padding-left: 5px;padding-top: 10px">
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
            ])?>
        </div>

        <div class="col-md-12">
            <center>
                <input type="submit" class="btn btn-primary" name="all" value="Поиск всех">
                <input type="submit" class="btn btn-primary" name="search" value="Поиск">
                <input type="submit" class="btn btn-warning" name="uder" value="Поиск удержаний">
            </center>
        </div>
        <?php ActiveForm::end();?>

    </form>
    <!---------------Конец живого поиска-------------------->

</div>


<style>
    .kv-table-caption {
        font-size: 1.1em;
        border: 1px solid #ddd;
        border-bottom: none;
        text-align: center;
        color: #2e5499;
</style>

<?php

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'showPageSummary' => true,
    'footerRowOptions'=>['style'=>'font-weight:bold;text-decoration: underline;'],
    'pager' => ['maxButtonCount' => 10],
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
            'attribute'=>'employee_id',
            'header'=>'ФИО',
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
            },

        ],

        [
            'attribute'=>'',
            'header'=>'Пациент',
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
        ],

        [
            'attribute'=>'is_special',
            'header'=>'Наименование услуги',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'200px'],

            'value' => function($model) {
                if ($model->is_special == "1" ){
                    return "Онлайн консультация по COVID19";
                }elseif($model->is_special == "0" ) {
                    return "Онлайн консультация";
                }else {
                    return "Расшифровка ЭКГ";
                }
            },
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(ConsultType::find()->distinct()->all(), 'consult', 'type'),
                //'data'=>ArrayHelper::map(Consult::find()->joinWith('contype')->orderBy(['id'=>SORT_DESC])->distinct()->all(), 'id', 'contype->type'),
                //'data'=>ArrayHelper::map(Employee::find()->joinWith('advisor')->orderBy('fullname')->all(), 'id', 'fullname'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true],

            ],

        ],


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

        [
            'attribute'=>'created',
            'header'=>'Дата оказания услуг',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return date('d.m.Y H:i', $model->created_at);
            }
        ],

        [
            'attribute'=>'',
            'pageSummary' => true,
            'header'=>'Сумма',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'100px'],
            'value'=>function($model) {
                return $model->payment->orderSumAmount;
            }
        ],

        [
            'attribute'=>'',
            'pageSummary' => true,
            'header'=>'Доход',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                return $model->payment->shopSumAmount;
            }
        ],


//        [
//            'attribute'=>'',
//            'header'=>'Статус',
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
//            'value'=>function($model) {
//                if ($model->is_end = 1){
//                    return "Завершена";
//                }else{
//                    return "Не завершена";
//                }
//            }
//
//        ],

//        [
//            'attribute'=>'',
//            'header'=>'Выплаты',
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
//            'value'=>function($model) {
//                if ($model->is_canceled == '0'){
//                    return "К выплате";
//                }elseif($model->is_canceled == '1'){
//                    return "Без выплаты";
//                }
//            }
//
//        ],


    ]
]); ?>
<!--Вернуться назад-->
<table class="table table-hover table-striped">
    <tr align="center">
        <td align="center"><input class="button" type="button" value="Вернуться назад" onclick="javascript:history.go(-1)" /></td>
    </tr>
</table>
</div>