<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use app\models\data\Organization;

$this->title = 'Добавить организацию';
$this->params['breadcrumbs'][] = ['label'=>'Организации', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<div class="row">
    <div class="col-md-8">
        <?= $form->field($model, 'name')->textInput(['maxlength'=>true]) ?>
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
</div>

<div class="row">
    <div class="col-md-4">
        <?= $form->field($model, 'inn')->textInput(['maxlength'=>true]) ?>
    </div>    
    <div class="col-md-4">
        <?= $form->field($model, 'kpp')->textInput(['maxlength'=>true]) ?>
    </div> 
    <div class="col-md-4">
        <?= $form->field($model, 'ogrn')->textInput(['maxlength'=>true]) ?>
    </div> 
</div>

<?= $form->field($model, 'address')->textInput(['maxlength'=>true]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>