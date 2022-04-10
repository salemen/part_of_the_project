<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="login-box-body">
    <h4 class="login-box-msg">Введите код заказа</h4>
    
    <?php $form = ActiveForm::begin() ?>
    
    <?= $form->field($model, 'code')->textInput(['placeholder'=>'Код заказа (7 цифр)'])->label(false) ?>
    
    <hr>
    
    <div class="row">
        <div class="col-md-6">
            <?= Html::submitButton('Найти заказ', ['class'=>'btn btn-primary btn-block']) ?>
        </div>
    </div>

    <?php ActiveForm::end() ?>  
</div>