<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\vaccine\VacAge;
use app\models\vaccine\VacSickness;
?>

<?php $form = ActiveForm::begin([
    'id'=>'sicks-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

<?= $form->field($model, 'age_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(VacAge::arrayDeclension(), 'id', 'value'),
    'pluginOptions'=>[
        'placeholder'=>'Укажите возраст'
    ]
])->label('Возраст') ?>

<?= $form->field($model, 'sicks')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(VacSickness::find()->orderBy('name')->all(), 'id', 'name'),
    'pluginOptions'=>[
        'multiple'=>true,
        'placeholder'=>'Укажите болезни, которыми вы переболели'
    ]
])->label('Болезни') ?>

<div class="form-group">
    <?= Html::submitButton('Поиск', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>