<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Условия', 'url'=>['index', 'anketa_id'=>$model->anketa_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id'=>'anketa-perm-form']) ?>

<?= $form->field($model, 'param_name')->dropDownList([
    'age'=>'Возраст',
    'sex'=>'Пол'
]) ?>

<?= Html::beginTag('div', ['class'=>'form-group field-anketapermission-value required'])?>
    <?= Html::tag('label', $model->getAttributeLabel('value'), ['class'=>'control-label', 'for'=>'anketapermission-value'])?>
    <?= Html::tag('div', null, ['id'=>'value-block','model_id'=>$model->id])?>
    <?= Html::tag('div', null, ['class'=>'help-block'])?>
<?= Html::endTag('div')?>


<?= $form->field($model, 'operator')->dropDownList([
    '=='=>'=',
    '!='=>'≠',
    '>'=>'>',
    '<'=>'<'
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'anketa_id'=>$model->anketa_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

<?php 
$this->registerJs('
getType();

$("#anketapermission-param_name").on("change", function() {
    getType();
    $("#anketapermission-operator").val(null);

    if ($(this).val() == "sex") {
        $("#anketapermission-operator option[value=\">\"]").css("display","none");
        $("#anketapermission-operator option[value=\"<\"]").css("display","none");
    } else {
        $("#anketapermission-operator option[value]").css("display","block");
    }
});

function getType() {
    $.ajax({
        url: "get-type",
        data: {
            type: $("#anketapermission-param_name").val(),
            model_id: $("#value-block").attr("model_id")
        },
        method: "post",
        success: function(result) {
            $("#value-block").html(result);
            $("#anketa-perm-form").yiiActiveForm("add", {
                id: "anketapermission-value",
                name: "AnketaPermission[value]",
                container: ".field-anketapermission-value",
                input: "#anketapermission-value",
                error: ".help-block",
                validate:  function (attribute, value, messages, deferred, $form) {
                    yii.validation.required(value, messages, {message: "Необходимо заполнить \"Значение\""});
                }
            });
        }
    });
}
');
?>