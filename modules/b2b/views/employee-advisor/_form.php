<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\employee\Employee;
use app\models\employee\EmployeePosition;

$this->title = $model->isNewRecord ? 'Добавить консультанта' : 'Изменить консультанта';
$this->params['breadcrumbs'][] = ['label'=>'Консультанты', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$orgIds = EmployeePosition::getOrgIds(); 
$emplArray = Employee::find()->joinWith('positionsDoctor')->where(['IN', 'org_id', $orgIds])->orderBy('fullname')->all();
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'employee_id')->widget(Select2::className(), [
    'data'=>ArrayHelper::map($emplArray, 'id', 'fullname'),
    'hideSearch'=>false,
    'pluginOptions'=>[
        'disabled'=>!$model->isNewRecord,
        'placeholder'=>'Выберите сотрудника'
    ]
]) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'cost')->textInput(['maxlenght'=>true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'cost_2nd')->textInput(['maxlenght'=>true]) ?>
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'status')->widget(Select2::className(), [
            'data'=>[10=>'Консультант активен', 0=>'Консультант неактивен']
        ]) ?>
    </div>    
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>