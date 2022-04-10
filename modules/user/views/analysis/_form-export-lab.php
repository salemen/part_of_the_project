<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
?>

<?php $form = ActiveForm::begin([
    'id'=>'firebird-form',
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'user_f')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'user_i')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'user_o')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'user_sex')->widget(Select2::className(), ['data'=>['Ж'=>'Женский', 'М'=>'Мужской'], 'pluginOptions'=>['placeholder'=>'']]) ?>

<?= $form->field($model, 'user_year')->widget(MaskedInput::className(), ['mask'=>'9999']) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary btn-submit']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('   
$("#firebird-form").on("submit", function (e) {
    var data = $(this).data("yiiActiveForm");
    if (data.validated) {
        $(".btn-submit").text("Обработка результатов...");
        $(".btn-submit").attr("disabled", "disabled");
        return true;
    }
});
');