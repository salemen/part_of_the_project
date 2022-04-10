<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Расчет факторов риска: Вычисление суточной нормы калорий';
$this->params['breadcrumbs'][] = 'Расчет факторов риска';

$form = ActiveForm::begin(['id'=>'calory-form']);

echo $form->field($model, 'activity')->radioList([1=>'Умеренная', 0=>'Низкая'], ['separator'=>'<br/>'])->label('Ваша дневная физическая активность')->error(false);

echo $form->field($model, 'sex')->radioList([0=>'Женщина', 1=>'Мужчина'], ['separator'=>'<br/>'])->label('Пол')->error(false);

echo $form->field($model, 'height')->textInput(['maxlength'=>true, 'min'=>0, 'type'=>'number'])->label('Рост, см')->error(false);

echo Html::tag('div', Html::label('Результат:') . Html::tag('div', null, ['id'=>'result']), ['class'=>'form-group']);

echo Html::submitButton('Раcсчитать', ['class'=>'btn btn-primary', 'style'=>'margin-right: 3px;']);

echo Html::a('Сохранить результат в личном кабинете', ['/site/save-to-profile'], [
    'class'=>'btn btn-default btn-modal',
    'id'=>'profile-save',
    'style'=>'display: none;'
]);

$form->end();

$this->registerJs('
iCheckInit();

$("form").on("beforeSubmit", function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        var activity = $(".checked input[name=\"DynamicModel[activity]\"]").val();
        var sex = $(".checked input[name=\"DynamicModel[sex]\"]").val();
        var height = $("input[name=\"DynamicModel[height]\"]").val();
        var num = (sex == 0) ? 10 : 20;
        var factor = (activity == 0) ? 32.5 : 37.5;
        var mass = height - (100 + (height - 100) / num);
        var calory = mass.toFixed(2) * factor;
        
        $("#result").html("Суточная норма: <b>" + calory + "</b> ккал.");
        
        var saveBtn = $("#profile-save");
        
        saveBtn.data("height", height);
        saveBtn.data("sex", sex);
        
        saveBtn.css("display", "inline-block");
    }
    
    return false;
}); 
');