<?php
use dosamigos\tinymce\TinyMce;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Описание страниц', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'page_url')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'page_meta')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'page_content')->widget(TinyMce::className()) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>