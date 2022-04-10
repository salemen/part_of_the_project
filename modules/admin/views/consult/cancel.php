<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'id'=>'mark-as-canceled-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'comment')->textInput(['maxlenght'=>true])->label('Причина отмены консультации') ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>