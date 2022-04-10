<?php
use borales\extensions\phoneInput\PhoneInput;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$passwordTemplate = '<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<div class="login-box-body">
    <h4 class="login-box-msg">Пожалуйста, заполните данную форму</h4>
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
    
    <?= Html::checkbox('agree', false, ['label'=>'я ознакомлен(а) с '
    . Html::a('Политикой конфиденциальности, ', ['/docs/confid.docx'])
    . Html::a(' c Пользовательским соглашением ', ['/docs/polz.docx'])
    . 'и'
    . Html::a(' согласен на Обработку персональных данных', ['/docs/datagree.docx'])
    ]) ?> 
    
    <hr>
    
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-primary btn-block btn-submit']) ?> 
        </div>
        <div class="col-md-6">
            <?= Html::a('У меня уже есть учетная запись', ['/site/login'], ['class'=>'btn btn-default btn-block btn-modal']) ?>
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
');