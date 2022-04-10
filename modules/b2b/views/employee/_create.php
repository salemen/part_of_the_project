<?php
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\EmployeePosition;

$this->title = 'Добавить сотрудника';
$this->params['breadcrumbs'][] = ['label'=>'Сотрудники', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$orgIds = EmployeePosition::getOrgIds();   
$depArray = Department::find()->where(['IN', 'org_id', $orgIds])->orderBy('name')->all();
$orgArray = Organization::find()->joinWith('positions', true, 'INNER JOIN')->where(['employee_id'=>Yii::$app->user->id])->orderBy('name')->all();
$posArray = EmployeePosition::find()->where(['is_doctor'=>true])->orderBy('empl_pos')->distinct()->all();
?>

<?php $form = ActiveForm::begin(['id'=>'empl-signup-form']) ?>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'user_f')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-4">
        <?= $form->field($model, 'user_i')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-4">
        <?= $form->field($model, 'user_o')->textInput(['maxlength'=>true]) ?>
    </div> 
</div> 

<div class="row">     
    <div class="col-md-3">
        <?= $form->field($model, 'user_birth')->widget(MaskedInput::className(), [
            'mask'=>'99.99.9999',
            'options'=>[
                'class'=>'form-control'
            ]
        ]) ?>        
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'sex')->widget(Select2::className(), [
            'data'=>[0=>'Женский', 1=>'Мужской'],
            'options'=>[
                'placeholder'=>'Пол'
            ]
        ]) ?>         
    </div>    
    <div class="col-md-6">
        <?= $form->field($model, 'city')->widget(Select2::className(), [
            'pluginOptions'=>[
                'ajax'=>[
                    'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                    'dataType'=>'json',
                    'delay'=>250,
                    'url'=>Url::to(['/data/city', 'keytext'=>true]) 
                ],
                'minimumInputLength'=>3,
                'placeholder'=>$model->getAttributeLabel('city'),
                'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                'templateSelection'=>new JsExpression('function (data) { return data.text; }')
            ]
        ]) ?>
    </div>  
</div>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'email')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-6">
        <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
            'jsOptions'=>[
                'preferredCountries'=>['ru']
            ]
        ]) ?>        
    </div>
</div>

<hr>

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
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary btn-submit']) ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
$("form").submit(function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        $(".btn-submit").text("Обработка...");
        $(".btn-submit").attr("disabled", "disabled");
        return true;
    }
});   
');