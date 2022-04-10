<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\employee\Employee;
use app\models\payments\Payments;
use app\models\consult\search\Consult;
use app\modules\b2b\models\ConsultType;
use kartik\select2\Select2;
use kartik\daterange\DateRangePicker;
use yii\web\JsExpression;
use app\models\data\Department;

if(!empty($_GET['Employee']['fullname'])) {
    $id1 = $_GET['Employee']['fullname'];
    $id = $id1[0];
    $name1 = Department::find()->select('name')->where(['id' => $id])->all();
    $name = $name1[0]['name'];
}

if (!empty($name)){
    $this->title = 'Удержания по сотруднику' .'_'.$name;
}else {
    $this->title = 'Удержания по сотруднику';
}
$this->params['breadcrumbs'][] = $this->title;

function showBtnGroup($model) {
//    $content = Html::button('Действия', ['class'=>'btn btn-success btn-xs dropdown-toggle', 'data'=>['toggle'=>'dropdown']]);
    $items = '';
//    $links = [
//        $model->is_end ? null : Html::a('Изменить консультанта', ['change-employee', 'id'=>$model->id], ['class'=>'btn-modal']),
//        Html::a($model->is_end ? 'Открыть консультацию' : 'Закрыть консультацию', ['change-end', 'id'=>$model->id]),
//        Html::a($model->is_canceled ? 'Просмотр причины отмены консультации' : 'Отменить консультацию', ['cancel', 'id'=>$model->id], ['class'=>'btn-modal']),
//        Html::a('Просмотр переписки', ['read-history', 'id'=>$model->id], ['class'=>'btn-modal'])
//    ];

//    if ($links) {
//        foreach ($links as $link) {
//            $items .= Html::tag('li', $link);
//        }
//    }

//    $content .= Html::tag('ul', $items, ['class'=>'dropdown-menu pull-right']);

//    return Html::tag('div', $content, ['class'=>'btn-group']);
} ?>


    <div class="employee-degree-index">


        <!-------Живой поиск--------->

        <form method="get" id="search" action="<?= Url::to(['consult-department/searchhold']) ?>">
            <?php $form = ActiveForm::begin(); ?>

            <div class="col-md-6" style="padding-right: 5px;padding-left: 5px;">
                <?= Html::tag('label', 'Сотрудник', ['class' => 'control-label']);?>
                <?php


                $model_cat = Employee::find()->all();
                foreach ($model_cat as $key => $item) {
                    $id = $key;
                    $model3 = $item;
                }

                $url = Url::toRoute(['/cart/dok-dep']);


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

            <div class="col-md-6" style="padding-right: 5px;padding-left: 5px;">
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
                <center> <input type="submit" class="btn btn-primary" name="search" value="Поиск всех">
                    <input type="submit" class="btn btn-warning" name="uder" value="Поиск удержаний">
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
echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'filterModel'=>$searchModel,
    'showPageSummary' => true,
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
            'attribute'=>'',
            'header'=>'Наименование услуги',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
//            'value'=>function($model) {if ($dataProvider[is_special] == 1 ){
//                return "Специальный";
//            }},

            'value' => function($data) {
                if ($data->is_special = 1){
                    return "Онлайн консультация";
                }elseif($data->is_special = 0) {
                    return "Специальная консультация";
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


        [
            'attribute'=>'',
            'header'=>'Сотрудник',
            'contentOptions'=>['class'=>'kv-align-middle'],
//            'filterType'=>GridView::FILTER_SELECT2,
//            'filterWidgetOptions'=>[
//                'data'=>ArrayHelper::map(Employee::find()->joinWith('advisor') ->andWhere(['>=', 'worker_id', 4])->orderBy('fullname')->all(), 'id', 'fullname'),
//                'hideSearch'=>false,
//                'options'=>['placeholder'=>'Фильтр'],
//                'pluginOptions'=>['allowClear'=>true]
//            ],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'value'=>function($model) {
                return ($model->employee) ? $model->employee->fullname : null;
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
            'pageSummary' => true,
            'header'=>'Стоимость',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'50px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return $model->payment->orderSumAmount;
            }
        ],
//        [
//            'attribute'=>'id',
//            'header'=>'Доход',
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
//            'mergeHeader'=>true,
//            'value'=>function($model) {
//                return $model->payment->shopSumAmount;
//            }
//        ],

        [
            'attribute'=>'',
            'header'=>'Дата отказа от услуг',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'mergeHeader'=>true,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                if (!empty($model->is_canceled)){
                    return date('d.m.Y H:i', $model->ended_at);
                }else { return "нет отказа";}

            }
        ],

        [
            'attribute'=>'id',
            'header'=>'Причина отказа',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'200px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return ($model->is_canceled) ? $model->comment : 'Нет причин или отказа';
            }
        ],

        [
            'attribute'=>'id',
            'header'=>'Сумма подлежащая удержанию',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'50px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return "?";
            }
        ],
//        [
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
            'attribute'=>'id',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'header'=>'Статус удержания',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'60px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return ($model->payment->isTest) ? 'Да' : 'Нет';
            }
        ],

       



//        [
//            'class'=>'kartik\grid\ActionColumn',
//            'template'=>'{actions}',
//            'buttons'=>[
//                'actions'=>function($url, $model) {
//                    return showBtnGroup($model);
//                }
//            ]
//        ]
    ]
]);