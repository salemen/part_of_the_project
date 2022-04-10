<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\checker\CheckerBodyparts;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Симптомы', 'url'=>['/admin/checker-symptoms/index']];
$this->params['breadcrumbs'][] = ['label'=>'Связь с категориями', 'url'=>['index', 'symptom_id'=>$model->symptom_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'bodypart_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(CheckerBodyparts::find()->where(['status'=>CheckerBodyparts::STATUS_ACTIVE])->orderBy('name')->all(), 'id', 'name'),
    'pluginOptions'=>[
        'placeholder'=>'Выберите часть тела'
    ]
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
');