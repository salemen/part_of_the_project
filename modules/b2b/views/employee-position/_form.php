<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\EmployeePosition;

$this->title = 'Добавить место работы';
$this->params['breadcrumbs'][] = ['label'=>'Сотрудники', 'url'=>['/b2b/employee/index']];
$this->params['breadcrumbs'][] = ['label'=>'Место работы', 'url'=>['index', 'employee_id'=>$model->employee_id]];
$this->params['breadcrumbs'][] = $this->title;

$user = Yii::$app->user;

$orgIds = EmployeePosition::findAll(['employee_id'=>$user->id]);
$orgArray = Organization::find()->joinWith('positions', true, 'INNER JOIN')->where(['employee_id'=>$user->id])->orderBy('name')->all();
$depArray = Department::find()->where(['IN', 'org_id', $orgIds])->orderBy('name')->all();
$posArray = EmployeePosition::find()->where(['is_doctor'=>true])->orderBy('empl_pos')->distinct()->all();
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'org_id')->widget(Select2::className(), [
            'data'=>ArrayHelper::map($orgArray, 'id', 'name'),
            'pluginOptions'=>[
                'placeholder'=>''
            ]
        ]) ?>
    </div>    
    <div class="col-md-4">
        <?= $form->field($model, 'empl_dep')->widget(Select2::className(), [
            'data'=>ArrayHelper::map($depArray, 'name', 'name'),
            'pluginOptions'=>[
                'placeholder'=>''
            ]
        ]) ?>       
    </div>
    <div class="col-md-4">
        <?= $form->field($model, 'empl_pos')->widget(Select2::className(), [
            'data'=>ArrayHelper::map($posArray, 'empl_pos', 'empl_pos'),
            'hideSearch'=>false,
            'pluginOptions'=>[
                'placeholder'=>''
            ]
        ]) ?>       
    </div>
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'emloyee_id'=>$model->employee_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>