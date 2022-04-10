<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Бланки осмотров', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'patient_id')->widget(Select2::className(), [
    'initValueText'=>($model->patient) ? ($model->patient->fullname . ' ' . $model->patient->phone) : $model->patient_id,
    'pluginOptions'=>[
        'allowClear'=>true,
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>['/data/patient']
        ],
        'minimumInputLength'=>3,
        'placeholder'=>'Укажите пациента',
        'tags'=>true,
        'templateResult'=>new JsExpression("function(data) { return (data.text + ' ' + ((data.phone === undefined) ? '' : data.phone)); }"),
        'templateSelection'=>new JsExpression("function (data) { return (data.text + ' ' + ((data.phone === undefined) ? '' : data.phone)); }")
    ],
    'theme'=>Select2::THEME_BOOTSTRAP
]) ?>

<?= $this->render("_part/{$model2->view}", ['form'=>$form, 'model'=>$model2]) ?>

<div class="form-group">
    <?= ($model->employee_id === Yii::$app->user->id) ? Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) : null ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php  ActiveForm::end() ?>