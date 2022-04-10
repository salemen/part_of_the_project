<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'id'=>'change-employee-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'employee_id')->widget(Select2::className(), [
    'initValueText'=>($model->employee_id) ? $model->employee->fullname : null,
    'pluginOptions'=>[
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>['/data/employee']            
        ],
        'minimumInputLength'=>3,
        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
        'templateSelection'=>new JsExpression('function (data) { return data.text; }')        
    ]    
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>