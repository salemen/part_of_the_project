<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Расчет факторов риска: Вычисление индекса курильщика';
$this->params['breadcrumbs'][] = 'Расчет факторов риска';

$form = ActiveForm::begin(['id'=>'smoky-form']);

echo $form->field($model, 'cigars')->textInput(['maxlength'=>true, 'min'=>0, 'type'=>'number'])->label('Кол-во выкуриваемых сигарет в день, шт')->error(false);

echo $form->field($model, 'experience')->textInput(['maxlength'=>true, 'min'=>0, 'type'=>'number'])->label('Стаж курения, лет')->error(false);

echo Html::tag('div', Html::label('Результат:') . Html::tag('div', null, ['id'=>'result']), ['class'=>'form-group']);

echo Html::submitButton('Расcчитать', ['class'=>'btn btn-primary']);

$form->end();

$this->registerJs('
$("form").on("beforeSubmit", function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        var cigars = $("input[name=\"DynamicModel[cigars]\"]").val();
        var experience = $("input[name=\"DynamicModel[experience]\"]").val();
        var result = cigars * experience / 20;
        var hobl = (result > 10) ? "<br><span style=\"color: orangered;\">Высокий фактор риска развития ХОБЛ.</span>" : "<br><span style=\"color: orange;\">Низкий фактор риска развития ХОБЛ.</span>";
        
        $("#result").html("Индекс курильщика: <b>" + result.toFixed(2) + "</b> " + hobl);
    }
    
    return false;
}); 
');