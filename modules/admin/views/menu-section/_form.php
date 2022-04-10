<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Разделы меню', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'slug')->textInput(['maxlength'=>true]) ?>
    </div>
</div>

<?= $form->field($model, 'is_hide_all')->checkbox() ?>

<?= $form->field($model, 'is_on_header')->checkbox() ?>

<?= $form->field($model, 'is_on_mega')->checkbox() ?>

<?= $form->field($model, 'is_on_footer')->checkbox() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>