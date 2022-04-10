<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;

$form = ActiveForm::begin([
    'action'=>'save',
    'id'=>'anketa-question_' . $question->id,    
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]);

echo Html::beginTag('div', ['style'=>'font-size: 20px; text-align: center;']);

echo $form->field($model, 'anketa_id')->hiddenInput(['value'=>$anketa_id])->label(false)->error(false);

echo $form->field($model, 'question_id')->hiddenInput(['value'=>$question->id])->label(false)->error(false);

echo $form->field($model, 'session_id')->hiddenInput(['value'=>$session_id])->label(false)->error(false);

echo $this->render('_part/question', [
    'form'=>$form,
    'model'=>$model,    
    'question'=>$question
]);

echo Html::submitButton('Ответить', ['class'=>'btn btn-lg btn-primary btn-submit-answer', 'style'=>'margin-top: 20px;']);

echo Html::endTag('div');

$form->end();

$this->registerJs('
iCheckInit(); 

$("form").on("beforeSubmit", function(e) {
    var submitBtn = $(".btn-submit-answer");
    submitBtn.text("Обработка...");
    submitBtn.attr("disabled", "disabled");
    
    $.ajax({
        data: $(this).serialize(),
        method: "post",
        success: function(result) {     
            var content = $("#anketa-content");
            var progress = $("#anketa-progress");
            
            if (result.is_end === true) {
                $.ajax({
                    data: { session_id: content.attr("data-session_id") },
                    method: "post",
                    url: "end"
                });
            } else {      
                progress.load("get-progress", {
                    count: content.attr("data-count"),
                    prev_question: result.question_id
                });
                content.load("get-form", {
                    anketa_id: content.attr("data-anketa_id"),
                    session_id: content.attr("data-session_id"),
                    prev_question: result.question_id
                });
            }
        },
        url: $(this).attr("action")
    });
    
    return false;
});
');