<?php
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="login-box-body">
    <h4 class="login-box-msg">Введите номер телефона, который был указан при регистрации</h4>
    <?php $form = ActiveForm::begin([
        'id'=>'pass-reset-form',
        'enableAjaxValidation'=>true,
        'validateOnChange'=>false,
        'validateOnBlur'=>false
    ]) ?>

    <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
        'jsOptions'=>[
            'preferredCountries'=>['ru']
        ]
    ])->label(false) ?>
    
    <hr>
    
    <div class="row">
        <div class="col-md-7">
            <?= Html::submitButton('Восстановить пароль!', ['class'=>'btn btn-primary btn-block btn-submit']) ?>
        </div>
        <div class="col-md-5">
            <?= Html::a('Вход на сайт', ['/site/login'], ['class'=>'btn btn-default btn-block btn-modal']) ?>
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