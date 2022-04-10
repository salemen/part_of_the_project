<?php
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Загрузить сотрудников списком';
$this->params['breadcrumbs'][] = ['label'=>'Сотрудники', 'url'=>['employee/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="alert alert-warning alert-dismissible">
    <h4><i class="icon fa fa-warning"></i> Внимание!</h4>
    Загружаемый документ должен быть документом Microsoft Excel (xlsx) и содержать в себе следующие столбцы:<br>
    1) ФИО сотрдуника<br>
    2) Пол (в формате М / Ж)<br>
    3) Дата рождения (в формате XX.XX.XXXX)<br>
    4) Город<br>
    5) Номер телефона сотрудника (в формате 7XXXXXXXXXX)<br>
    6) E-mail сотрудника<br>
    7) Должность сотрудника<br>
    <br>
    Образец документа: <?= Html::a('import.xlsx', '/storage/b2b/empl-import-example.xlsx') ?>
</div>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'org_id')->widget(Select2::classname(), [
    'data'=>ArrayHelper::map($orgArray, 'id', 'name')
]) ?>

<?= $form->field($model, 'file')->widget(FileAPI::className(), ['settings'=>['url'=>['/b2b/site/fileapi-upload']]]) ?>

<div class="form-group">
    <?= Html::submitButton('Добавить', ['class'=>'btn btn-success']) ?>
</div>

<?php ActiveForm::end() ?>