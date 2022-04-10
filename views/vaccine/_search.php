<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\vaccine\VacAge;
?>

<?php $form = ActiveForm::begin([
    'action'=>['sicks'],
    'id'=>'search-sicks',
    'layout'=>'inline',
    'method'=>'get'
]) ?>

<?= $form->field($model, 'age_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(VacAge::arrayDeclension(), 'id', 'value'),
    'pluginOptions'=>[
        'placeholder'=>'Укажите возраст'
    ]
])->label('Возраст') ?>

<div class="form-group">
    <?= Html::submitButton('Применить', ['class'=>'btn btn-sm btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>