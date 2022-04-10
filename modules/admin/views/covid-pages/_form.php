<?php
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\covid\models\CovidPages;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: COVID-19 Страницы', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'controller')->widget(Select2::className(), [
    'data'=>CovidPages::controllerArray()
]) ?>

<?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'content')->widget(TinyMce::className()) ?>

<?= $form->field($model, 'status')->widget(Select2::className(), [
    'data'=>[10=>'Активен', 0=>'Удален']
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>