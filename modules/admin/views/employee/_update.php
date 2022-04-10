<?php
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$this->title = 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Сотрудники', 'url'=>['index2']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id'=>'empl-signup-form']) ?>

<div class="row">
    <div class="col-md-12">
        <?= $form->field($model, 'fullname')->textInput(['maxlength'=>true]) ?>
    </div>
</div> 

<div class="row"> 
    <div class="col-md-4">
        <?= $form->field($model, 'email')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-4">
        <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
            'jsOptions'=>[
                'preferredCountries'=>['ru']
            ]
        ]) ?>
    </div>
    <div class="col-md-4">
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
    <div class="col-md-12">
        <?= $form->field($model, 'password')->textInput(['maxlength'=>true, 'placeholder'=>'Оставьте поле пустым, если пароль менять не нужно']) ?>
    </div> 
    
    <div class="col-md-12">
        <?= $form->field($model, 'status')->widget(Select2::className(), [
            'data'=>[10=>'Активен', 0=>'Удален']
        ]) ?>
    </div> 
</div>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary btn-submit']) ?>
    <?= Html::a('Отмена', ['index2'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>