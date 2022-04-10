<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Справочник: Сотрудники', 'url'=>['/admin/employee/index']];
$this->params['breadcrumbs'][] = ['label'=>'Специальные регалии сотрудника', 'url'=>['index', 'employee_id'=>$model->employee_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'type')->widget(Select2::className(), [
    'data'=>[10=>'Должность']
]) ?>

<?= $form->field($model, 'value')->textInput(['maxlength'=>true]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
