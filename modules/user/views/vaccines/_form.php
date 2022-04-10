<?php
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
?>

<div class="alert alert-warning" style="font-size: 13px;">
    Если Вам одновременно ставились несколько вакцин, пожалуйста заполните данную форму для каждой вакцины отдельно. Это позволит корректно сформировать ваш календарь вакцинации.
</div>    

<?php $form = ActiveForm::begin([
    'id'=>'vaccines-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'created_at')->widget(DatePicker::classname(), [
    'type'=>DatePicker::TYPE_INPUT,
    'options'=>['placeholder'=>'Укажите дату проведения вакцинации'],
    'pluginOptions'=>[
        'autoclose'=>true,
        'endDate'=>date("d.m.Y")
    ]
]) ?>

<?= $form->field($model, 'vaccine')->widget(Select2::className(), [
    'initValueText'=>$model->vaccine,
    'pluginOptions'=>[
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>Url::to(['/data/vaccine', 'keytext'=>true])     
        ],
        'minimumInputLength'=>3,
        'placeholder'=>'Укажите наименование вакцины',
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