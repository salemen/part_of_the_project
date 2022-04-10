<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\research\ResearchIndex;

$this->title = 'Копировать нормы';
$this->params['breadcrumbs'][] = ['label'=>'Показатели', 'url'=>['index', 'type_id'=>$model->type_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-warning">
    <h4><i class="icon fa fa-warning"></i> Внимание!</h4>
    Имеющиеся нормы в целевом показателе будут полностью заменены копируемыми нормами.
</div>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'copy_from')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(ResearchIndex::find()->where(['type_id'=>$model->type_id])->orderBy('name')->all(), 'id', 'name'),
    'hideSearch'=>false,
    'pluginOptions'=>['placeholder'=>'Выберите']
]) ?>

<?= $form->field($model, 'copy_to')->widget(Select2::className(), [
    'data'=>ArrayHelper::map(ResearchIndex::find()->where(['type_id'=>$model->type_id])->orderBy('name')->all(), 'id', 'name'),
    'hideSearch'=>false,
    'pluginOptions'=>['placeholder'=>'Выберите']
]) ?>

<?= $form->field($model, 'type_id')->hiddenInput()->label(false) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>