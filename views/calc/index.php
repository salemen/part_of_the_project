<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Расчет факторов риска: Вычисление индекса массы тела';
$this->params['breadcrumbs'][] = 'Расчет факторов риска';

$form = ActiveForm::begin(['id'=>'index-form']);

echo $form->field($model, 'height')->textInput(['maxlength'=>true, 'min'=>0, 'type'=>'number'])->label('Рост, см')->error(false);

echo $form->field($model, 'weight')->textInput(['maxlength'=>true, 'min'=>0, 'type'=>'number'])->label('Вес, кг')->error(false);

echo Html::tag('div', Html::label('Результат:') . Html::tag('div', null, ['id'=>'result']), ['class'=>'form-group']);

echo Html::submitButton('Раcсчитать', ['class'=>'btn btn-primary', 'style'=>'margin-right: 3px;']);

echo Html::a('Сохранить результат в личном кабинете', ['/site/save-to-profile'], [
    'class'=>'btn btn-default btn-modal',
    'id'=>'profile-save',
    'style'=>'display: none;'
]);

$form->end();

$this->registerJs('
$("form").on("beforeSubmit", function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        var height = $("input[name=\"DynamicModel[height]\"]").val();
        var weight = $("input[name=\"DynamicModel[weight]\"]").val();        
        var imt = weight / Math.pow(height / 100, 2);
        var imtColor = "orangered";        
        var imtResult = "";        
        var riskColor = "green";
        var riskResult = "";
        
        if (imt <= 16) {
            imtResult = "Выраженный дефицит массы тела.";
            riskResult = "Низкий";
        } else if (imt > 16 && imt <= 18.5) {
            imtResult = "Дефицит массы тела.";
            riskResult = "Низкий";
        } else if (imt > 18.5 && imt <= 25) {
            imtResult = "Нормальная масса тела.";
            imtColor = "green";
            riskResult = "Обычный";
        } else if (imt > 25 && imt <= 30) {
            imtResult = "Избыточная масса тела.";
            riskColor = "orange";
            riskResult = "Повышенный";
        } else if (imt > 30 && imt <= 35) {
            imtResult = "Ожирение первой степени.";
            riskColor = "orangered";
            riskResult = "Высокий";
        } else if (imt > 35 && imt <= 40) {
            imtResult = "Ожирение второй степени.";
            riskColor = "orangered";
            riskResult = "Очень высокий";
        } else if (imt > 40) {
            imtResult = "Ожирение третьей степени.";
            riskColor = "orangered";
            riskResult = "Чрезвычайно высокий";
        }
        
        var imtHtml = "Индекс массы тела: <b>" + imt.toFixed(2) + "</b> кг/м<SUP>2</SUP>.<br>";        
        var imtResHtml = "<span style=\"color: " + imtColor + ";\">" + imtResult + "</span><br>";
        var riskHtml = "<span style=\"color: " + riskColor + ";\">" + riskResult + "</span> риск сердечно-сосудистых заболеваний.";
        
        $("#result").html(imtHtml + imtResHtml + riskHtml);   
        
        var saveBtn = $("#profile-save");
        
        saveBtn.data("height", height);
        saveBtn.data("weight", weight);
        
        saveBtn.css("display", "inline-block");
    }
    
    return false;
}); 
');