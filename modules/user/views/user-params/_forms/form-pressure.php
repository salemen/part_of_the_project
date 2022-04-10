<?php
use kartik\datetime\DateTimePicker;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<?php $form = ActiveForm::begin([
    'id'=>'form-' . $param_name
]) ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'created_at')->widget(DateTimePicker::className(), [
            'options'=>['placeholder'=>'Выберите дату и время'],
            'pluginOptions'=>[
                'autoclose'=>true,
                'endDate'=>date("d.m.Y H:i", time() + 86400),
                'format'=>'dd.mm.yyyy hh:ii'             
            ]
        ]) ?>
    </div>
</div>
<div class="row">
    <div class="col-md-6 col-sm-6 col-xs-6">
        <?= $form->field($model, 'systole')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>
    </div>
    <div class="col-md-6 col-sm-6 col-xs-6">
        <?= $form->field($model, 'diastole')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>
    </div>
</div>

<?= ($model->isNewRecord) ? null: $form->field($model, 'delete')->checkbox() ?>

<div class="form-group" style="text-align: center">
    <?= Html::button('Сохранить', ['class'=>'submit btn btn-primary', 'model_id'=>$model_id, 'param_name'=>$param_name]) ?>
    <?= Html::button('Отмена', ['param_name'=>$param_name, 'class'=>'cancel btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>