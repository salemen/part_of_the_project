<?php
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Тесты', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'desc')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'emails')->widget(Select2::className(), [
    'data'=>ArrayHelper::map($model->testEmails, 'email', 'email'),
    'hideSearch'=>false,
    'options'=>['multiple'=>true],
    'pluginOptions'=>[
        'placeholder'=>'Укажите E-mail для рассылки',
        'tags'=>true
    ]
]) ?>

<?= $form->field($model, 'img')->widget(FileAPI::className(), [
    'settings'=>[
        'accept'=>'.jpg, .jpeg, .png',
        'url'=>['/site/fileapi-upload']
    ]
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

