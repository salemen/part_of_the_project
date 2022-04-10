<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$passwordTemplate = '<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<div class="login-box-body">
    <h4 class="login-box-msg">Пожалуйста, авторизуйтесь</h4>
    <?php $form = ActiveForm::begin([
        'id'=>'login-form',
        'enableAjaxValidation'=>true,
        'validateOnChange'=>false,
        'validateOnBlur'=>false
    ]) ?>

    <?= $form->field($model, 'identity')->textInput([
        'class'=>'form-control', 
        'placeholder'=>$model->getAttributeLabel('identity')
    ])->label(false) ?>
    
    <?= $form->field($model, 'password', ['template'=>$passwordTemplate])->passwordInput([
        'class'=>'form-control',
        'placeholder'=>$model->getAttributeLabel('password')
    ])->label(false) ?>

    <div class="row">
        <div class="col-md-8">
            <?= $form->field($model, 'rememberMe')->checkbox() ?>
        </div>
        <div class="col-md-4">
            <?= Html::submitButton('Войти', ['class'=>'btn btn-primary btn-block']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php
$this->registerJs('
iCheckInit();
');