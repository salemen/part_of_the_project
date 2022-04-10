<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Экспорт';
$this->params['breadcrumbs'][] = ['label'=>'Рентгенография', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'start_date')->input('date') ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'end_date')->input('date') ?>
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Экспорт', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>