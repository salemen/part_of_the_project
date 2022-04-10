<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Банковские реквизиты';
$this->params['breadcrumbs'][] = ['label'=>'Организации', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'bank')->textInput(['maxlength'=>true]) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'inn')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-4">
        <?= $form->field($model, 'kpp')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-4">
        <?= $form->field($model, 'ogrn')->textInput(['maxlength'=>true]) ?>
    </div> 
</div>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'bik')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-4">
        <?= $form->field($model, 'check_c')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-4">
        <?= $form->field($model, 'check_r')->textInput(['maxlength'=>true]) ?>
    </div> 
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>