<?php
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'desc')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'file')->widget(FileAPI::className(), [
    'settings'=>[
        'accept'=>'.doc, .docx, .pdf',
        'url'=>['/admin/site/fileapi-upload']
    ]
]) ?>

<div style="text-align: center">
    <?= ($model->file) ? $form->field($model, 'delete_file')->checkbox() : null ?>
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>