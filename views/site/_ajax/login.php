<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$passwordTemplate = '<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<div class="login-box-body" style="padding: 20px 1px 20px 1px;">
    <h4 class="login-box-msg">Пожалуйста, авторизуйтесь</h4>
    <?php $form = ActiveForm::begin([
        'id'=>'login-form',
        'enableAjaxValidation'=>true,
        'fieldConfig'=>[
            'errorOptions'=>[
                'class'=>'help-block',
                'encode'=>false                 
             ]
        ],
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
    
    <hr>    
    
    <div class="row">
        <div class="col-md-5">
            <?= Html::a('Я забыл(а) свой пароль', ['/site/pass-reset'], ['class'=>'btn btn-default btn-block btn-modal']) ?>      
        </div>
        <div class="col-md-7">
            <?= Html::a('Зарегистрировать новую <br class="hidden-sm hidden-md hidden-xl hidden-lg" />учетную запись', ['/site/signup'], ['class'=>'btn btn-warning btn-block btn-modal']) ?>
        </div>
    </div>
    <?php ActiveForm::end() ?>
</div>

<?php
$this->registerJs('
iCheckInit();
');