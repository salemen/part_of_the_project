<?php
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\menu\MenuSection;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Пункты меню', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'section_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(MenuSection::find()->where(['status'=>10])->orderBy('name')->all(), 'id', 'name'),
    'pluginOptions'=>[
        'placeholder'=>'Выберите раздел'
    ]
]) ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'url')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-6">
        <?= $form->field($model, 'class_default')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'class_guest')->textInput(['maxlength'=>true]) ?>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'photo')->widget(FileAPI::className(), [
            'crop'=>true,
            'cropResizeWidth'=>300,
            'cropResizeHeight'=>300,
            'cropResizeMaxWidth'=>300,
            'cropResizeMaxHeight'=>300,
            'jcropSettings'=>[
                'aspectRatio'=>1,
                'bgColor'=>'#ffffff',
                'maxSize'=>[500, 500],
                'minSize'=>[100, 100],
                'keySupport'=>false,
                'selection'=>'100%'
            ],
            'settings'=>[
                'accept'=>'.jpg, .jpeg, .png',
                'url'=>['/site/fileapi-upload']
            ]
        ]) ?>
    </div>
    <div class="col-md-6">
        <?= $form->field($model, 'photo_small')->widget(FileAPI::className(), [
            'crop'=>true,
            'cropResizeWidth'=>48,
            'cropResizeHeight'=>48,
            'cropResizeMaxWidth'=>48,
            'cropResizeMaxHeight'=>48,
            'jcropSettings'=>[
                'aspectRatio'=>1,
                'bgColor'=>'#ffffff',
                'maxSize'=>[500, 500],
                'minSize'=>[48, 48],
                'keySupport'=>false,
                'selection'=>'100%'
            ],
            'settings'=>[
                'accept'=>'.jpg, .jpeg, .png',
                'url'=>['/site/fileapi-upload']
            ]
        ]) ?>
    </div>
</div>

<?= $form->field($model, 'is_blank')->checkbox() ?>

<?= $form->field($model, 'is_on_header')->checkbox() ?>

<?= $form->field($model, 'is_on_mega')->checkbox() ?>

<?= $form->field($model, 'is_on_footer')->checkbox() ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>