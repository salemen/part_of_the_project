<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$passwordTemplate = '{label}<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<div class="login-box-body">
    <h4 class="login-box-msg">Задайте новый пароль</h4>
    <?php $form = ActiveForm::begin([
        'id'=>'pass-set-form',
        'enableAjaxValidation'=>true,
        'validateOnChange'=>false,
        'validateOnBlur'=>false
    ]) ?>

    <?= $form->field($model, 'password', ['template'=>$passwordTemplate])->passwordInput(['maxlength'=>true, 'class'=>'form-control']) ?>
    
    <hr>
    
    <div class="row">
        <div class="col-md-12">
            <?= Html::submitButton('Сохранить пароль', ['class'=>'btn btn-primary btn-block btn-submit']) ?>
        </div>
    </div>
    
    <?php ActiveForm::end() ?>
</div>

<?php
$this->registerJs('
$("form").submit(function (e) {
    var data = $(this).data("yiiActiveForm");
    if (data.validated) {
        $(".btn-submit").attr("disabled", "disabled");
        return true;
    }
});
');