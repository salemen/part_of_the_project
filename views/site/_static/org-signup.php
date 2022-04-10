<?php
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;

$passwordTemplate = '<div class="input-group">{input}<div class="input-group-addon"><i class="fa fa-eye btn-pass-reveal" style="cursor: pointer;"></i></div></div>{error}';
?>

<div style="margin-top: 10px;">
    <h4 class="login-box-msg">Зарегистрируйтесь, чтобы начать работу</h4>
        <?php $form = ActiveForm::begin(['id'=>'org-signup-form']) ?>
    
        <div class="row">
            <div class="col-md-8">
                <?= $form->field($model, 'org_name')->textInput(['placeholder'=>$model->getAttributeLabel('org_name')])->label(false) ?>
            </div>    
            <div class="col-md-4">
                <?= $form->field($model, 'org_city')->widget(Select2::className(), [
                    'pluginOptions'=>[
                        'ajax'=>[
                            'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                            'dataType'=>'json',
                            'delay'=>250,
                            'url'=>Url::to(['/data/city', 'keytext'=>true]) 
                        ],
                        'minimumInputLength'=>3,
                        'placeholder'=>$model->getAttributeLabel('org_city'),
                        'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                        'templateSelection'=>new JsExpression('function (data) { return data.text; }')
                    ]
                ])->label(false) ?>
            </div>
        </div>
    
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'org_inn')->textInput(['placeholder'=>$model->getAttributeLabel('org_inn')])->label(false) ?>
            </div>    
            <div class="col-md-4">
                <?= $form->field($model, 'org_kpp')->textInput(['placeholder'=>$model->getAttributeLabel('org_kpp')])->label(false) ?>
            </div> 
            <div class="col-md-4">
                <?= $form->field($model, 'org_ogrn')->textInput(['placeholder'=>$model->getAttributeLabel('org_ogrn')])->label(false) ?>
            </div> 
        </div>
        
        <?= $form->field($model, 'org_address')->textInput(['placeholder'=>$model->getAttributeLabel('org_address')])->label(false) ?>

        <hr>
        
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'user_f')->textInput(['placeholder'=>$model->getAttributeLabel('user_f')])->label(false) ?>
            </div>    
            <div class="col-md-4">
                <?= $form->field($model, 'user_i')->textInput(['placeholder'=>$model->getAttributeLabel('user_i')])->label(false) ?>
            </div> 
            <div class="col-md-4">
                <?= $form->field($model, 'user_o')->textInput(['placeholder'=>$model->getAttributeLabel('user_o')])->label(false) ?>
            </div> 
        </div>        
        
        <div class="row">
            <div class="col-md-6">
                <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
                    'jsOptions'=>[
                        'preferredCountries'=>['ru']
                    ]
                ])->label(false) ?>
            </div>    
            <div class="col-md-6">
                <?= $form->field($model, 'email')->textInput(['placeholder'=>$model->getAttributeLabel('email')])->label(false) ?>
            </div> 
        </div> 
        
        <?= $form->field($model, 'password', ['template'=>$passwordTemplate])->passwordInput([
            'class'=>'form-control',
            'placeholder'=>$model->getAttributeLabel('password')
        ])->label(false) ?>
        
        <?= Html::checkbox('agree', false, ['label'=>'Я согласен(а) на обработку персональных данных'])?>
        
        <hr>
        
        <div class="row">
            <div class="col-md-7">
                <?= Html::submitButton('Зарегистрироваться', ['class'=>'btn btn-primary btn-block btn-submit']) ?> 
            </div>
            <div class="col-md-5">
                <?= Html::a('Вернуться на сайт', '/', ['class'=>'btn btn-default btn-block btn-flat', 'style'=>'background-color: #FFFFFF;']) ?>
            </div>
        </div>    

    <?php ActiveForm::end() ?>    
</div>

<?php
$this->registerJs('
iCheckInit();
$(".btn-submit").prop("disabled", true);
$("input").on("ifChecked", function(event) {
    $(".btn-submit").prop("disabled", false);
});
$("input").on("ifUnchecked", function(event) {
    $(".btn-submit").prop("disabled", true);
});
$("form").submit(function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        $(".btn-submit").text("Обработка...");
        $(".btn-submit").attr("disabled", "disabled");
        return true;
    }
});   
');