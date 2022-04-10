<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$sex = ['мужской', 'женский'];
$sexMap = array_combine($sex, $sex);
?>

<?php $form = ActiveForm::begin([
    'id'=>'patient-form',
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'u_fam')->textInput(['maxlength'=>true])->error(false) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'u_ima')->textInput(['maxlength'=>true])->error(false) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'u_otc')->textInput(['maxlength'=>true])->error(false) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'u_data_ros')->widget(MaskedInput::className(), [
            'mask'=>'99.99.9999'
        ])->error(false) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'u_pol')->widget(Select2::className(), [
            'data'=>$sexMap,
            'pluginOptions'=>[
                'placeholder'=>'Укажите пол'
            ]
        ])->error(false) ?>
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
$("form").on("beforeSubmit", function(e) {    
    $.ajax({
        data: $(this).serialize(),
        method: "post",
        success: function(response) {            
            var select2 = $("#flurajurnal-f_fio_id");
            var selectedValues = new Array();
            
            selectedValues.push(response.id);
            select2.append(\'<option value="\' + response.id + \'">\' + response.text + \'</option>\').val(selectedValues).trigger("change");
            
            $("#modal-form").modal("hide");
        },
        url: $(this).attr("action")
    });
    
    return false;
});
');