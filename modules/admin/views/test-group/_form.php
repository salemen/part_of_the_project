<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Тесты', 'url'=>['test/index']];
$this->params['breadcrumbs'][] = ['label'=>'Группы', 'url'=>['index', 'test_id'=>$model->test_id]];
$this->params['breadcrumbs'][] = $this->title;

?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'name')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'test_id'=>$model->test_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>
