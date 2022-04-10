<?php

use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\data\Department;
use app\models\oms\Oms;
use kartik\date\DatePicker;
use yii\web\JsExpression;


$this->params['breadcrumbs'][] = ['label'=>'Бланки', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin() ?>

    <div class="row">

        <div class="col-md-4">
            <?= $form->field($model, 'user_f')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'user_i')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'user_o')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'user_birth')->widget(MaskedInput::className(), [
                'mask'=>'99.99.9999',
                'options'=>[
                    'class'=>'form-control'
                ]
            ]) ?>
        </div>
    </div>


<?= $form->field($model, 'who_calls')->textInput(['maxlength'=>true]) ?>

    <div class="row">
        <div class="col-md-2">
            <?= $form->field($model, 'phone')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model, 'city')->widget(Select2::className(), [
                'initValueText'=>($model->city) ? $model->city : null,
                'id'=>'cat-id',
                'options'=>['placeholder'=>'Укажите город'],
                'pluginOptions'=>[
                    'ajax'=>[
                        'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                        'dataType'=>'json',
                        'delay'=>250,
                        'url'=>Url::to(['/data/city', 'keytext'=>true])
                    ],
                    'minimumInputLength'=>2,
                    'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                    'templateSelection'=>new JsExpression('function (data) { return data.text; }')
                ]
            ]) ?>
        </div>
        <div class="col-md-3">
            <?php
            if(isset($model->proposal->patient)){
                $model->address = $model->address.' '.$model->proposal->patient->data->address;
            }elseif(isset($model->proposal->employee)){
                $model->address = $model->address.' '.$model->proposal->employee->data->address;
            }
            ?>
            <?= $form->field($model, 'address')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'guide')->textInput(['maxlength'=>true]) ?>
        </div>
    </div>

<?= $form->field($model, 'reason')->widget(Select2::className(), [
    'data'=>['Вызов врача на дом'=>'Вызов врача на дом',
        'Консультация врача'=>'Консультация врача',
        'Диагнностика на дому'=>'Диагнностика на дому'
    ]
]) ?>

<?= $form->field($model, 'complaint')->textarea(['class'=>'form-control', 'rows'=>4, 'style'=>'resize: vertical;']) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'visit_date')->widget(DatePicker::className(), [
                'value' => $model->visit_date,
                'options' => ['placeholder' => 'Введите дату'],
                'pluginOptions'=>[
                    'constrainInput'=> true,
                    'autoclose'=>true,
                    'startDate'=> date("H") >= 23 ? date("d.m.Y",microtime(true)+(60*60*24)) : date("d.m.Y"),
                    'format'=>'dd.mm.yyyy'
                ]
            ]) ?>
        </div>

    </div>

<?= $form->field($model, 'payment')->checkbox(['id'=>'is_payment']) ?>

    <div id="payment_attributes">
        <?= $form->field($model, 'cost')->textInput(['id'=>'cost', 'maxlength'=>true]) ?>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?php if(!empty($model->proposal->patient->data)) {
                echo $form->field($model->proposal->patient->data, 'clinic')->textInput(['maxlength'=>true])->label('Поликлиника');
            }elseif(!empty($model->proposal->employee->data)) {
                echo $form->field($model->proposal->employee->data, 'clinic')->textInput(['maxlength'=>true])->label('Поликлиника');
            }else {
                echo $form->field($model, 'dep_id')->widget(Select2::className(), [
                    'data' => ArrayHelper::map(Department::find()->where(['is_santal' => true, 'status' => 10])->orderBy('name')->all(), 'id', function ($item) {
                        return $item->name . ' (' . $item->address . ')';
                    })])->label('Поликлиника');
            }?>
        </div>
        <div class="col-md-6">
            <?php if(!empty($model->proposal->patient->data)) {
                echo $form->field($model->proposal->patient->data, 'polis_oms_number')->widget(MaskedInput::className(), [
                    'mask'=>'9999999999999999',
                    'options'=>[
                        'class'=>'form-control'
                    ]
                ])->label('Полис ОМС');
            }elseif(!empty($model->proposal->employee->data)) {
                echo $form->field($model->proposal->employee->data, 'polis_oms_number')->widget(MaskedInput::className(), [
                    'mask'=>'9999999999999999',
                    'options'=>[
                        'class'=>'form-control'
                    ]
                ])->label('Полис ОМС');
            }?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
toggleInput($("#is_payment").is(":checked") ? true : false);
$("#is_payment").on({
    ifChecked: function() {
        toggleInput(true);
    },
    ifUnchecked: function() {
        toggleInput(false);
    }
});

function toggleInput(value) {
    $("#cost").prop("required", value);
    $("#cost").val(value ? $("#cost").val() : "");
    $("#payment_attributes").css("display", (value ? "block" : "none"));
}
');