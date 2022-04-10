<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Расчет факторов риска: Вычисление идеальной массы тела';
$this->params['breadcrumbs'][] = 'Расчет факторов риска';

$form = ActiveForm::begin(['id'=>'brok-form']);

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
        var sex = $(".checked input[name=\"DynamicModel[sex]\"]").val();
        var height = $("input[name=\"DynamicModel[height]\"]").val();
        var num = (sex == 0) ? 10 : 20;
        var mass = height - (100 + (height - 100) / num);
        
        $("#result").html("Идеальная масса тела: <b>" + mass.toFixed(2) + "</b> кг.");
        
        var saveBtn = $("#profile-save");
        
        saveBtn.data("height", height);
        saveBtn.data("sex", sex);
        
        saveBtn.css("display", "inline-block");
    }
    
    return false;
}); 
');