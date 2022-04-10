<?php
use kartik\date\DatePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\research\ResearchIndex;
use app\models\research\ResearchUnit;
?>

<?php $form = ActiveForm::begin([
    'id'=>'analysis-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'created_at')->widget(DatePicker::className(), [
    'options'=>['placeholder'=>'Выберите дату'],
    'pluginOptions'=>[
        'autoclose'=>true,
        'endDate'=>date("d.m.Y"),
        'format'=>'dd.mm.yyyy'
    ]
]) ?>

<?= $form->field($model, 'index_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(
        ResearchIndex::find()
            ->joinWith('type')
            ->orderBy(['research_type.name'=>SORT_ASC, 'research_index.name'=>SORT_ASC])
            ->all(), 'id', 'name', function ($item) {
                return $item->type->name;
            }),
    'hideSearch'=>false,
    'pluginOptions'=>[
        'placeholder'=>'Выберите показатель'
    ]
]) ?>

<?= $form->field($model, 'unit_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(ResearchUnit::find()->orderBy('name')->all(), 'id', 'name'),
    'hideSearch'=>false,
    'pluginOptions'=>[
        'placeholder'=>'Выберите единицу измерения'
    ]
]) ?>

<?= $form->field($model, 'value')->textInput(['maxlength'=>true]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
$(document).on("change", "#useranalysis-index_id", function() {
    populate("#useranalysis-index_id", "#useranalysis-unit_id");
});  

function populate(from, target) {
    var index_id = $(from).val();
    
    $.ajax({
        data: {index_id: index_id},        
        method: "post",        
        success: function (result) {
            var $select = $(target);
            var defOptions = $select.data("select2").options.options;
            var options = {
                allowClear: true,
                data: result.data,
                placeholder: defOptions.placeholder,
                theme: defOptions.theme, 
                width: defOptions.width
            };
            
            $select.empty();
            $select.select2(options);
        },
        url: "/user/analysis/populate-filter"
    });
}
');