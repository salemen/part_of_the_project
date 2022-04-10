<?php


use yii\widgets\ActiveForm;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\helpers\Html;
use app\models\employee\Employee;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;
use app\modules\b2b\models\ConsultType;

$this->title = 'Статистика';

?>

<div class="employee-degree-index">


    <!-----Живой поиск------->

    <form method="get" id="search" action="<?= Url::to(['dep-all/index']) ?>">
        <?php $form = ActiveForm::begin(); ?>

        <div class="col-md-6" style="padding-right: 5px;padding-left: 15px; padding-top: 10px;">
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
                <input type="submit" class="btn btn-primary" name="uder" value="Поиск удержаний">
            </center>
        </div>
        <?php ActiveForm::end();?>
        
    </form>



