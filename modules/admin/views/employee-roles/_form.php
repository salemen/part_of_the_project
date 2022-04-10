<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = 'Добавить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Роли и разрешения', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'employee_id')->widget(Select2::className(), [
    'initValueText'=>null,
    'options'=>['placeholder'=>'Выберите'],
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
])->label('Сотрудник') ?>

<?= $form->field($model, 'roles')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(Yii::$app->getAuthManager()->getRoles(), 'name', 'description'),
    'options'=>['multiple'=>true, 'placeholder'=>'Выберите']
])->label('Роли') ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-success']) ?>
</div>

<?php ActiveForm::end() ?>