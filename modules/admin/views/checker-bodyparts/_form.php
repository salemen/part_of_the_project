<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\medical\MedicalSection;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Категории', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'sections')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(MedicalSection::find()->where(['status'=>10])->orderBy('name')->all(), 'id', 'name'),
    'hideSearch'=>false,
    'pluginOptions'=>[
        'allowClear'=>true,
        'multiple'=>true,
        'placeholder'=>'Выберите направление(я)'
    ]
]) ?>

<?= $form->field($model, 'sex_m')->checkbox() ?>

<?= $form->field($model, 'sex_w')->checkbox() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
');