<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$user = Yii::$app->user;
?>

<div class="login-box-body">
    <h4 class="login-box-msg">Заполните E-mail и/или Номер телефона</h4>
    <?php $form = ActiveForm::begin([
        'id'=>'save-to-profile-form',
        'enableAjaxValidation'=>true,
        'validateOnChange'=>false,
        'validateOnBlur'=>false
    ]) ?>

    <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'disabled'=>!$user->isGuest, 'placeholder'=>$model->getAttributeLabel('email')])->label(false) ?>
    
    <?= $form->field($model, 'phone')->widget(MaskedInput::className(), [
        'mask'=>'99999999999',
        'options'=>[
            'class'=>'form-control',
            'disabled'=>!$user->isGuest,
            'placeholder'=>$model->getAttributeLabel('phone')
        ]
    ])->label(false) ?>
    
    <?php if ($params) {
        foreach ($params as $key=>$param) {
            echo $form->field($model, "params[$key]")->hiddenInput(['value'=>$param])->label(false)->error(false);
        }
    } ?>
    
    <?= $form->field($model, 'validate')->hiddenInput()->label(false) ?>
    
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary btn-block']) ?>
    
    <?php ActiveForm::end() ?>
</div>