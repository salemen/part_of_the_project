<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$passwordTemplate = '{label}<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<?php $pass_form = ActiveForm::begin([
    'enableAjaxValidation'=>true,
    'validateOnBlur'=>false,
    'validateOnChange'=>false    
]) ?>

<?= $pass_form->field($pass, 'password', ['template'=>$passwordTemplate])->passwordInput(['maxlength'=>true, 'class'=>'form-control']) ?>

<?= $pass_form->field($pass, 'newPassword', ['template'=>$passwordTemplate])->passwordInput(['maxlength'=>true, 'class'=>'form-control']) ?>

<div class="form-group">
    <?= Html::submitButton('Изменить пароль', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>