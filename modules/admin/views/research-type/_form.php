<?php
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\research\ResearchLabType;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Виды исследований', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'name_alt')->textInput(['maxlength'=>true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'rel_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map(ResearchLabType::find()->orderBy('name')->all(), 'id', 'name'),
            'pluginOptions'=>[
                'placeholder'=>'Выберите вид исследования из лаборатории'
            ]
        ]) ?>
    </div>
</div>

<?= $form->field($model, 'icon')->widget(FileAPI::className(), [
    'crop'=>true,
    'cropResizeWidth'=>64,
    'cropResizeHeight'=>64,
    'cropResizeMaxWidth'=>64,
    'cropResizeMaxHeight'=>64,
    'jcropSettings'=>[
        'aspectRatio'=>1,
        'bgColor'=>'#ffffff',
        'maxSize'=>[300, 300],
        'minSize'=>[64, 64],
        'keySupport'=>false,
        'selection'=>'100%'
    ],
    'settings'=>[
        'accept'=>'.jpg, .jpeg, .png',
        'url'=>['/site/fileapi-upload']
    ]
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>