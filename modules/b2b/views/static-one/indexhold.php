<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
Use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use app\models\employee\Employee;
use app\models\payments\Payments;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\models\consult\search\Consult;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Поиск сотрудника';
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
        <?php
        $model_cat = Consult::find()->all();
        foreach ($model_cat as $key => $item) {
            $id = $key;
            $model3 = $item;
        }
        ?>

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
            <center> <input type="submit" class="btn btn-primary" name="search" value="Поиск сотрудника"></center></
</div>
<?php ActiveForm::end();?>

</form>

<!---------------Конец живого поиска-------------------->


</div>


