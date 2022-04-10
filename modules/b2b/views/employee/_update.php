<?php
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = 'Редактировать сотрудника';
$this->params['breadcrumbs'][] = ['label'=>'Сотрудники', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-6">
        <?= $form->field($model, 'fullname')->textInput(['maxlength'=>true, 'disabled' => true,]) ?>
    </div>    
    <div class="col-md-3">
        <?= $form->field($model, 'user_birth')->widget(MaskedInput::className(), [
            'mask'=>'99.99.9999',
            'options'=>[
                'class'=>'form-control',
                'disabled' => true,
            ]
        ]) ?> 
    </div> 
    <div class="col-md-3">
        <?= $form->field($model, 'sex')->widget(Select2::className(), [
            'data'=>[0=>'Женский', 1=>'Мужской'],
            'options'=>[
                'placeholder'=>'Пол',
                'disabled' => true,
            ]
        ]) ?> 
    </div> 
</div> 

<?= $form->field($model, 'city')->widget(Select2::className(), [
    'pluginOptions'=>[
        'ajax'=>[
            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
            'dataType'=>'json',
            'delay'=>250,
            'url'=>Url::to(['/data/city', 'keytext'=>true]) 
        ],
        'disabled' => true,
        'minimumInputLength'=>3,
        'placeholder'=>$model->getAttributeLabel('city'),
        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
        'templateSelection'=>new JsExpression('function (data) { return data.text; }')
    ]
]) ?>

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

<?= $form->field($model, 'status')->widget(Select2::className(), [
    'data'=>[0=>'Неактивен', 10=>'Активен']
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>