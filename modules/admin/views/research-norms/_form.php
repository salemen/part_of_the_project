<?php
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\research\ResearchIndex;
use app\models\research\ResearchUnit;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Виды исследований', 'url'=>['research-type/index']];
$this->params['breadcrumbs'][] = ['label'=>'Показатели', 'url'=>['research-index/index', 'type_id'=>$index->type_id]];
$this->params['breadcrumbs'][] = ['label'=>'Нормы', 'url'=>['index', 'index_id'=>$model->index_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'unit_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(ResearchUnit::find()->orderBy('name')->all(), 'id', 'name'),
    'hideSearch'=>false,
    'pluginOptions'=>[
        'placeholder'=>'Выберите единицу измерения'
    ]
]) ?>

<?php if ($index->grade_id == ResearchIndex::GRADE_COL) { ?>

<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'norm_m_min')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-2">
        <?= $form->field($model, 'norm_m_max')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'norm_w_min')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-2">
        <?= $form->field($model, 'norm_w_max')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-2">
        <?= $form->field($model, 'norm_pr_min')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-2">
        <?= $form->field($model, 'norm_pr_max')->textInput(['maxlength'=>true]) ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-2">
        <?= $form->field($model, 'norm_min')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-2">
        <?= $form->field($model, 'norm_max')->textInput(['maxlength'=>true]) ?>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($index, 'interp_down')->widget(TinyMce::className()) ?>
    </div> 
    <div class="col-md-6">
        <?= $form->field($index, 'interp_up')->widget(TinyMce::className()) ?>
    </div> 
</div>

<?php } else { ?>

    <?= $form->field($model, 'is_norm')->checkbox() ?>

    <?= $form->field($model, 'norm_value')->textInput(['maxlength'=>true]) ?>

    <hr>
    
    <?= $form->field($model, 'interp')->widget(TinyMce::className()) ?>

<?php } ?>

<?= $form->field($index, 'comment')->textarea(['rows'=>2, 'style'=>'resize: vertical;']) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
');