<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\b2b\models\Cardio_oneSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="cardio-one-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'employee_id') ?>

    <?= $form->field($model, 'patient_id') ?>

    <?= $form->field($model, 'ekg_date') ?>

    <?= $form->field($model, 'patient_height') ?>

    <?php // echo $form->field($model, 'patient_weight') ?>

    <?php // echo $form->field($model, 'patient_sicks') ?>

    <?php // echo $form->field($model, 'patient_drugs') ?>

    <?php // echo $form->field($model, 'patient_target') ?>

    <?php // echo $form->field($model, 'is_end') ?>

    <?php // echo $form->field($model, 'is_payd') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
