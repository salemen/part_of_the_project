<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\anketa\AnketaRiskGroup;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Категории рисков', 'url'=>['anketa-risk-category/index', 'anketa_id'=>AnketaRiskGroup::getAnketaId($model->category_id)]];
$this->params['breadcrumbs'][] = ['label'=>'Группы рисков', 'url'=>['index', 'category_id'=>$model->category_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'risk_name')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>

<?= $form->field($model, 'tactic')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'type')->dropDownList([
    AnketaRiskGroup::NOTYPE=>'Без типа',
    AnketaRiskGroup::TYPE_AND=>'И',
    AnketaRiskGroup::TYPE_OR=>'ИЛИ',
    AnketaRiskGroup::TYPE_SUM=>'Сумма баллов',
]) ?>

<?= $form->field($model, 'sex')->dropDownList([
    null=>'Для всех',
    0=>'Для женщин',
    1=>'Для мужчин',
]) ?>

<?= $form->field($model, 'value')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>

<?= $form->field($model, 'operator')->dropDownList([
    '=='=>'=',
    '!='=>'≠',
    '>'=>'>',
    '<'=>'<'
], ['prompt'=>'']) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'category_id'=>$model->category_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
var value = $(".field-anketariskgroup-value");
var operator = $(".field-anketariskgroup-operator");

$(document).ready(function() {
    $("#anketariskgroup-type").trigger("change");
});

$(document).on("change", "#anketariskgroup-type", function() {
    var type = Number($(this).val());
    
    switch (type) {
        case 0:    
        case 10:
        case 20:
            value.css("display","none");
            operator.css("display","none");
            $("#anketariskgroup-value").val(null);
            $("#anketariskgroup-operator").val(null);
            break;
        case 30:
            value.css("display","block");
            operator.css("display","block");
            break;
    }
});
'); ?>
