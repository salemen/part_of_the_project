<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Флюорография', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$groups = ['ОВП2', 'ОВП3', 'ОВП4', 'ОВП6', 'ОВП7', 'ОВП8', 'ОВП9', 'САНТАЛЬ'];
$groupsMap = array_combine($groups, $groups);
$obls = ['ОГК', 'ППН'];
$oblsMap = array_combine($obls, $obls);
$vrachs = ['Воробьев А.Е.', 'Евтюшкин Н.Е.'];
$vrachsMap = array_combine($vrachs, $vrachs);
$pays = ['Платный', 'ОМС', 'ДМС'];
$paysMap = array_combine($pays, $pays);
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-11 col-sm-10 col-xs-9">
        <?= $form->field($model, 'f_fio_id')->widget(Select2::className(), [
            'initValueText'=>$model->patient ? implode(' ', [$model->patient->u_fam, $model->patient->u_ima, $model->patient->u_otc, "({$model->patient->u_data_ros})"]) : $model->f_fio_id,
            'options'=>['id'=>'fio_id'],
            'pluginOptions'=>[
                'ajax'=>[
                    'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                    'dataType'=>'json',
                    'delay'=>250,
                    'url'=>['/med/pz-patient/find']
                ],
                'minimumInputLength'=>3,
                'placeholder'=>'Выберите пациента',
                'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                'templateSelection'=>new JsExpression('function(data) { return data.text; }')
            ]
        ]) ?>
    </div>
    <div class="col-md-1 col-sm-2 col-xs-3">
        <?= Html::a('<i class="fa fa-plus"></i>', ['/med/pz-patient/create'], ['class'=>'btn btn-danger btn-block btn-flat add-patient', 'style'=>'height: 34px; margin-top: 26px;', 'title'=>'Добавить пациента']) ?>
    </div>
</div>

<?= $form->field($model, 'f_n_medk')->textInput(['maxlength'=>true]) ?>

<div class="row">
    <div class="col-md-9">
        <?= $form->field($model, 'f_organis')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label class="control-label">Шаблоны организации</label><br>
            <div style="padding: 4px 0px;">
                <?= Html::a('Д/с №', '#', ['class'=>'btn btn-default btn-xs template']) ?>
                <?= Html::a('Гимназия №', '#', ['class'=>'btn btn-default btn-xs template']) ?>
                <?= Html::a('СОШ №', '#', ['class'=>'btn btn-default btn-xs template']) ?>
                <?= Html::a('Школа №', '#', ['class'=>'btn btn-default btn-xs template']) ?>
            </div>
        </div>
    </div>    
</div>    

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'f_num_snimk')->input('number') ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'f_obl_issled')->widget(Select2::className(), [
            'data'=>$oblsMap
        ]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'f_o_group')->widget(Select2::className(), [            
            'data'=>$groupsMap,
            'pluginOptions'=>[
                'allowClear'=>true,
                'placeholder'=>'Выберите подразделение'
            ]
        ]) ?>
    </div>
</div>

<?php if (!$model->isNewRecord) { ?>

    <?= $form->field($model, 'f_diagnos')->textarea(['rows'=>2, 'style'=>'resize: vertical;']) ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'f_sakl_opis')->textarea(['rows'=>8, 'style'=>'resize: vertical;']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'f_sakl')->textarea(['rows'=>8, 'style'=>'resize: vertical;']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'f_norm_group')->widget(Select2::className(), [
                'data'=>[1=>'Норма', 2=>'Патология']
            ])->error(false) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'f_sakl_vrach')->widget(Select2::className(), [
                'data'=>$vrachsMap
            ])->error(false) ?>
        </div>
    </div>

<?php } ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();

$(document).on("click", ".template", function(e) {
    var value = $(this).html();    
    $("#flurajurnal-f_organis").val(value).focus();
    e.preventDefault();
});

$(document).on("click", ".add-patient", function(e) {
    $("#modal-form").modal();
    $(".modal-body").html(\'<div style="padding: 40px; text-align: center;"><i class="fa fa-spinner fa-spin fa-3x fa-fw" style="color: #193e85;"></i></div>\');
    $(".modal-body").load($(this).attr("href"));
    
    e.preventDefault();
});
');