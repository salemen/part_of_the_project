<?php
use kartik\date\DatePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'id'=>'form-' . $param_name
]) ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'created_at')->widget(DatePicker::className(), [
            'options'=>['placeholder'=>'Выберите дату'],
            'pluginOptions'=>[
                'autoclose'=>true,
                'endDate'=>date("d.m.Y"),
                'format'=>'dd.mm.yyyy'             
            ]
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'temperature')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>
    </div>
</div>

<?= ($model->isNewRecord) ? null: $form->field($model, 'delete')->checkbox() ?>

<div class="form-group" style="text-align: center;">
    <?= Html::button('Сохранить', ['class'=>'submit btn btn-primary', 'model_id'=>$model_id, 'param_name'=>$param_name]) ?>
    <?= Html::button('Отмена', ['param_name'=>$param_name, 'class'=>'cancel btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>