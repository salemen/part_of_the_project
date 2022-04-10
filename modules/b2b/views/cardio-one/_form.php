<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\b2b\models\Cardio_one */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cardio-one-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'employee_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patient_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'ekg_date')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patient_height')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patient_weight')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'patient_sicks')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'patient_drugs')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'patient_target')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'is_end')->textInput() ?>

    <?= $form->field($model, 'is_payd')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
