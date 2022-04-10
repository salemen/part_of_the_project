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


$this->title = 'Удержания по сотруднику';
$this->params['breadcrumbs'][] = $this->title;

?>


<div class="employee-degree-index">

                        <!-------Живой поиск--------->

    <form method="get" id="search" action="<?= Url::to(['consult-one/searchhold']) ?>">
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
 </div>
    <!---------------Конец живого поиска-------------------->



<?php
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
            'attribute'=>'is_special',
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
            'attribute'=>'employee_id',
            'header'=>'Сотрудник',
            'contentOptions'=>['class'=>'kv-align-middle'],
           'filterType'=>GridView::FILTER_SELECT2,
           'filterWidgetOptions'=>[
               'data'=>ArrayHelper::map(Employee::find()->joinWith('advisor') ->andWhere(['>=', 'worker_id', 4])->orderBy('fullname')->all(), 'id', 'fullname'),
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
            'attribute'=>'worker_id',
            'header'=>'Статус',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'filterType'=>GridView::FILTER_SELECT2,
            'filterWidgetOptions'=>[
                'data'=>ArrayHelper::map(Employee::find()->where(['>=', 'worker_id', 4])->joinWith('advisor')->orderBy('fullname')->all(), 'id', 'worker_id'),
                'hideSearch'=>false,
                'options'=>['placeholder'=>'Фильтр'],
                'pluginOptions'=>['allowClear'=>true]
            ],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
            'value' => function($data) {
                if ($data->employee->worker_id = 4){
                    return "Самозанятые";
                }elseif($data->employee->worker_id = 5) {
                    return "Физические лица";
                }else {
                    return "-";
                }
            },


        ],

        [
            'attribute'=>'id',
            'header'=>'Стоимость',
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return $model->payment->orderSumAmount;
            }
        ],


        [
            'attribute'=>'',
            'header'=>'Дата отказа от услуг',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'mergeHeader'=>true,
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'value'=>function($model) {
                    if (!empty($model->history->created_at)){
                        return date('d.m.Y H:i', $model->history->created_at);
                    }else { return "нет отказа";}

            }
        ],

        [
            'attribute'=>'id',
            'header'=>'Причина отказа',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return ($model->is_canceled) ? $model->is_canceled : 'Нет причин или отказа';
            }
        ],

        [
            'attribute'=>'id',
            'header'=>'Сумма подлежащая удержанию',
            'contentOptions'=>['class'=>'kv-align-middle'],
            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'width'=>'150px'],
            'mergeHeader'=>true,
            'value'=>function($model) {
                return "?";
            }
        ],

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

    ]
]);