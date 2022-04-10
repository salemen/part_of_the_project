<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$this->title = 'Проверка результатов теста на COVID-19';
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2" style="background: url('/sars/bg/bg3.png') no-repeat center; background-size: cover; border: 1px solid #ddd; border-radius: 4px; margin-top: 15px;">
        <div class="row">
            <div class="col-md-8 col-md-offset-2 text-center" style="padding: 30px;">
                <?= Html::tag('h4', '<img src="/sars/icon/covid.png" style="width: 30px;"> Пожалуйста заполните форму', ['class'=>'text-primary', 'style'=>'margin-top: 10px;']) ?>
                <hr>
                <?php $form = ActiveForm::begin([
                    'id'=>'covid-form',
                    'validateOnChange'=>false,
                    'validateOnBlur'=>false
                ]) ?>

                <?= $form->field($model, 'number')->textInput(['maxlength'=>true]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Получить результат!', ['class'=>'btn btn-primary btn-lg']) ?>
                </div>

                <?php ActiveForm::end() ?>
            </div>         
        </div>
    </div>  
</div>

<?php
$this->registerJs('
$("#covid-form").on("beforeSubmit", function() {
    $.ajax({
        data: $(this).serialize(),
        method: "post",
        url: $(this).attr("action"),
        success: function (response) {
            $("#modal-form").modal();
            $(".modal-body").html(response);
        }
    });
    
    return false;
});    
');