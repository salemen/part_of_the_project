<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Менеджер: Консультанты', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'is_special')->checkbox() ?>

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
        'disabled'=>true,
        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
        'templateSelection'=>new JsExpression('function (data) { return data.text; }')        
    ]    
]) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'cost')->textInput(['maxlenght'=>true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'cost_2nd')->textInput(['maxlenght'=>true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'status')->widget(Select2::className(), [
            'data'=>[10=>'Консультант активен', 0=>'Консультант неактивен']
        ]) ?>
    </div>    
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
');