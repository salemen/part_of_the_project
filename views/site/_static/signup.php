<?php
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\captcha\Captcha;

$passwordTemplate = '<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>
<style>
    .form-control {
        background: #74c3fa2e;
    }
</style>
<div class="login-box-body" style="padding-top: 10px!important;">
    <h4 class="login-box-msg" style="padding-bottom: 30px;">Пожалуйста, заполните данную форму</h4>
    <?php $form = ActiveForm::begin([
        'id'=>'signup-form',
        'enableAjaxValidation'=>true,
        'validateOnChange'=>false,
        'validateOnBlur'=>false
    ]) ?>

    <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
        'jsOptions'=>[
            'preferredCountries'=>['ru']
        ]
    ])->label(false) ?>

    <?= $form->field($model, 'email')->textInput([
        'class'=>'form-control',
        'placeholder'=>$model->getAttributeLabel('email')
    ])->label(false) ?>

    <?= $form->field($model, 'password', ['template'=>$passwordTemplate])->passwordInput([
        'placeholder'=>$model->getAttributeLabel('password')
    ])->label(false) ?>

    <style>
        @media (min-width: 991px) {
            #my-captcha-image {
                padding-left: 35px;
            }
        }
    </style>


    <?= $form->field($model, 'captcha', ['enableAjaxValidation' => false])->widget(Captcha::className(),[
        'options' => ['placeholder'=>'введите проверочный код'],
        'imageOptions' =>[
            'id' => 'my-captcha-image'
        ],
        'template' => '<div class="form-group">
                    <div>{image}<a href="javascript:;" id="refresh-captcha">
                  <span class="logotip"> обновить проверочный код</span>
                  <span class="logotip-min">обновить код</span></a><div></div>
                    <div class="form-group">
                    {input}
                    </div>',
    ])->label(false) ?>

    <?= Html::checkbox('agree', false, ['label'=>'Я согласен(а) на обработку персональных данных']) ?>

    <hr>

    <div class="row">
        <div class="col-md-7">
            <?= Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-primary btn-block btn-submit']) ?>
        </div>
        <div class="col-md-5">
            <?= Html::a('Вход на сайт', '/site/login', ['class'=>'btn btn-default btn-block login-buttons']) ?>
        </div>
    </div>

    <?php ActiveForm::end() ?>
</div>

<?php
$this->registerJs('
iCheckInit();
$(".btn-submit").prop("disabled", true);
$("input").on("ifChecked", function(event) {
    $(".btn-submit").prop("disabled", false);
});
$("input").on("ifUnchecked", function(event) {
    $(".btn-submit").prop("disabled", true);
});
');?>

<?php $this->registerJs("
    $('#refresh-captcha').on('click', function(e){
        e.preventDefault();

        $('#my-captcha-image').yiiCaptcha('refresh');
    })
"); ?>


