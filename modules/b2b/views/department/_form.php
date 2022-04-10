<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить подразделение' : 'Редактировать подразделение';
$this->params['breadcrumbs'][] = ['label'=>'Организации', 'url'=>['/b2b/organization/index']];
$this->params['breadcrumbs'][] = ['label'=>'Подразделения', 'url'=>['index', 'org_id'=>$model->org_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>  

<?= $form->field($model, 'address')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'short_address')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'status')->widget(Select2::className(), [
    'data'=>[0=>'Неактивен', 10=>'Активен']
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'org_id'=>$model->org_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>