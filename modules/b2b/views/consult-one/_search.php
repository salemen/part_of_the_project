<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\b2b\models\Consult_oneSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="consult-one-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'employee_id') ?>

    <?= $form->field($model, 'patient_id') ?>

    <?= $form->field($model, 'comment') ?>

    <?= $form->field($model, 'dep_id') ?>

    <?php // echo $form->field($model, 'e_hide') ?>

    <?php // echo $form->field($model, 'p_hide') ?>

    <?php // echo $form->field($model, 'is_canceled') ?>

    <?php // echo $form->field($model, 'is_end') ?>

    <?php // echo $form->field($model, 'is_payd') ?>

    <?php // echo $form->field($model, 'is_special') ?>

    <?php // echo $form->field($model, 'created_at') ?>

    <?php // echo $form->field($model, 'ended_at') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
