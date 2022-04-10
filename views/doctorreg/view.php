<?php

use yii\helpers\Html;
use borales\extensions\phoneInput\PhoneInput;
use yii\widgets\ActiveForm;

$passwordTemplate = '<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<style>
a {
    color: #1864f8;
}

</style>

<center>
<div class="login-box-body col-md-5">
    <h4>Регистрация врача</h4>

    <?php $form = ActiveForm::begin([
        
    ]) ?>

    <?= $form->field($model, 'surname')->textInput([
        'class'=>'form-control',
        'placeholder'=>$model->getAttributeLabel('Фамилия')
    ])->label(false) ?>

    <?= $form->field($model, 'username')->textInput([
        'class'=>'form-control',
        'placeholder'=>$model->getAttributeLabel('Имя')
    ])->label(false) ?>

    <?= $form->field($model, 'patronymic')->textInput([
        'class'=>'form-control',
        'placeholder'=>$model->getAttributeLabel('Отчество')
    ])->label(false) ?>

    <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
        'jsOptions'=>[
            'preferredCountries'=>['ru']
        ]
    ])->label(false) ?>

    <?= $form->field($model, 'email')->textInput([
        'class'=>'form-control',
        'placeholder'=>$model->getAttributeLabel('email (необязательно)')
    ])->label(false) ?>

   
    <p><input type="checkbox" name="technologies[]" value="" />ознакомлен(а) с <a href=/docs/polz.pdf target=\"_blank\" >пользовательским соглашением</a>, а также с <a href=/docs/confid.pdf target=\"_blank\" >
    политикой конфидециальности</a> и согласен (согласна) с условиями обработки персональных данных.<br>
    Ознакомиться с <a href=/docs/oferta.pdf target=\"_blank\" >договорами оферты</a> </p>

   <!--  <p><input type="checkbox" name="technologies[]" value="" />ознакомлен(а) с <a href=/docs/confid.pdf target=\"_blank\" >
    политикой конфидециальности</a> и согласен(согласна с условиями обработки персональных данных)</p> -->

    
        <center><div>
            <?= Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-primary btn-block btn-submit btn-submit2']) ?> 
        </div></center>
    


    <?php ActiveForm::end() ?>    
</div> </center>

<div class="login-box-body col-md-7 logotip" style="padding-top:34px; padding-bottom:33px;">

   <center> <?= Html::img('@web/img/crop-doctors-shaking-hands 2.png', ['alt' => 'Логотип']) ?> </center>

</div>    

<?php
$this->registerJs('
iCheckInit();
$(".btn-submit").prop("disabled", true);
$("input").on("ifChecked", function(event) {
    $(".btn-submit ").prop("disabled", false);
});
$("input").on("ifUnchecked", function(event) {
    $(".btn-submit").prop("disabled", true);
});

iCheckInit();
$(".btn-submit2").prop("disabled", true);
$("input").on("ifChecked", function(event) {
    $(".btn-submit2").prop("disabled", false);
});
$("input").on("ifUnchecked", function(event) {
    $(".btn-submit2").prop("disabled", true);
});
');