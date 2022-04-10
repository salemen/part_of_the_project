<?php
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'id'=>'diagnosis-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
    'type'=>DatePicker::TYPE_INPUT,
    'options'=>['placeholder'=>'Укажите дату установления диагноза'],
    'pluginOptions'=>[
        'autoclose'=>true,
        'endDate'=>date("d.m.Y")
    ]
]) ?>

<?= $form->field($model, 'diagnosis')->widget(Select2::className(), [
    'initValueText'=>$model->diagnosis,
    'pluginOptions'=>[
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>Url::to(['/data/mkb', 'keytext'=>true])           
        ],
        'minimumInputLength'=>3,
        'placeholder'=>'Укажите код / диагноз МКБ',
        'tags'=>true,
        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
        'templateSelection'=>new JsExpression('function(data) { return data.text; }')
    ],
    'theme'=>Select2::THEME_BOOTSTRAP
]) ?>

<?= $form->field($model, 'employee')->widget(Select2::className(), [
    'initValueText'=>$model->employee,    
    'pluginOptions'=>[
        'allowClear'=>true,
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>Url::to(['/data/employee', 'keytext'=>true])      
        ],
        'minimumInputLength'=>3,
        'placeholder'=>'Укажите специалиста',
        'tags'=>true,
        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
        'templateSelection'=>new JsExpression('function (data) { return data.text; }')
    ],
    'theme'=>Select2::THEME_BOOTSTRAP
]) ?>

<?= $form->field($model, 'comment')->textarea(['rows'=>3, 'style'=>'resize: vertical;']) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>