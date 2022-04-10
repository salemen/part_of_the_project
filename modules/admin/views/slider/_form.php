<?php
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Слайдер', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textInput(['maxlenght'=>true]) ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'file')->widget(FileAPI::className(), [
            'settings'=>[
                'accept'=>'.jpg, .jpeg, .png',
                'url'=>['/site/fileapi-upload']
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'url_href')->textInput(['maxlenght'=>true]) ?>
    </div>
</div>

<?= $form->field($model, 'show_main')->checkbox() ?>

<?= $form->field($model, 'show_research')->checkbox() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>