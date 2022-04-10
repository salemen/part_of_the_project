<?php
use dosamigos\tinymce\TinyMce;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\employee\EmployeePosition;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Симптомы и болезни', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$tinymceConfig = Yii::$app->params['tinymce'];
$tinymceConfig['clientOptions']['images_upload_url'] = '/site/upload-checker';
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>

<?= $form->field($model, 'content')->widget(TinyMce::className(), $tinymceConfig) ?>

<hr>

<?= $form->field($model, 'specs')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(EmployeePosition::find()->where(['is_doctor'=>true])->orderBy('empl_pos')->all(), 'empl_pos', 'empl_pos'),
    'hideSearch'=>false,
    'options'=>['multiple'=>true],
    'pluginOptions'=>[
        'placeholder'=>'Выберите специальности',
        'tags'=>true
    ]
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>