<?php
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: COVID-19 Карты', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'covid_hospital')->widget(TinyMce::className()) ?>

<?= $form->field($model, 'covid_test')->widget(TinyMce::className()) ?>

<?= $form->field($model, 'covid_vaccine')->widget(TinyMce::className()) ?>

<?= $form->field($model, 'status')->widget(Select2::className(), [
    'data'=>[10=>'Активен', 0=>'Удален']
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>