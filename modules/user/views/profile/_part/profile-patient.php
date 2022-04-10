<?php
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;
use app\models\data\Department;
use yii\helpers\ArrayHelper;
use app\models\user\UserData;
use app\models\OMS\Oms;
use app\models\patient\Patient;
?>

<?php $form = ActiveForm::begin([
    'method' => 'post'
]) ?>
<?php
$items = Yii::$app->params['items'];
$params = Yii::$app->params['params'];
$items2 = Yii::$app->params['items2'];
$params2 = Yii::$app->params['params2'];
$user_data = Yii::$app->params['user_data'];
?>

<div class="row">
    <div class="col-md-6">
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'user_f')->textInput(['maxlength'=>true])->label('Фамилия*',['class' => 'someClass'])?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'user_i')->textInput(['maxlength'=>true])->label('Имя*',['class' => 'someClass'])?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'user_o')->textInput(['maxlength'=>true])->label('Отчество*',['class' => 'someClass'])?>
            </div>
        </div>

    </div>

    <div class="col-md-3">
        <?= $form->field($model, 'sex')->widget(Select2::className(), [
            'data'=>[0=>'Женский', 1=>'Мужской'],
            'options'=>[
                'placeholder'=>'Пол'
            ]
        ])->label('Пол*',['class' => 'someClass']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'user_birth')->widget(MaskedInput::className(), [
            'mask'=>'99.99.9999',
            'options'=>[
                'class'=>'form-control'
            ]
        ])->label('Дата рождения*',['class' => 'someClass'])?>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <?= $form->field($model, 'city')->widget(Select2::className(), [
            'initValueText'=>($model->city) ? $model->city : null,
            'pluginOptions'=>[
                'ajax'=>[
                    'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                    'dataType'=>'json',
                    'delay'=>250,
                    'url'=>Url::to(['/data/city', 'keytext'=>true])
                ],
                'minimumInputLength'=>3,
                'placeholder'=>'Укажите город',
                'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                'templateSelection'=>new JsExpression('function (data) { return data.text; }')
            ]
        ])->label('Город*',['class' => 'someClass'])?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'email')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'phone')->textInput(['maxlength'=>true, 'class'=>'form-control']) ?>
    </div>
    <div class="col-md-3">
        <?= $form->field($model, 'snils')->widget(MaskedInput::className(), [
            'mask'=>'999-999-999 99',
            'options'=>[
                'class'=>'form-control'
            ]
        ]) ?>
    </div>

</div>

    <div class="col-md-12" style="padding-bottom: 10px; padding-right: 5px;">
        <label for="radio-1">Вы прикреплены к медицинской организации ЦСМ-Санталь?&nbsp;&nbsp;&nbsp;</label>
        <input id="polis_exists" name="motive" type="checkbox">
    </div>

<div  id="polis_attributes" class="row">
    <div class="col-md-4">
        <?= $form->field($user_data, 'clinic')->dropDownList($items,$params)
            ->label('Моя поликлиника',['class' => 'someClass'])?>
    </div>

    <div class="col-md-4">
        <?= $form->field($user_data, 'polis_oms_org')->dropDownList($items2,$params2)
            ->label('Страховая компания ОМС',['class' => 'someClass'])?>
    </div>

    <div class="col-md-4">
        <?= $form->field($user_data, 'polis_oms_number')->textInput(['id'=>'polis_oms_number', 'class'=>'form-control' , 'maxlength'=>'16', 'autocomplete'=>"off"]) ?>
    </div>
</div>
 

<?= $form->field($model, 'photo')->widget(FileAPI::className(), [
    'crop'=>true,
    'cropResizeWidth'=>250,
    'cropResizeHeight'=>300,
    'jcropSettings'=>[
        'aspectRatio'=>0.83,
        'bgColor'=>'#ffffff',
        'maxSize'=>[500, 600],
        'minSize'=>[100, 120],
        'keySupport'=>false,
        'selection'=>'100%'
    ],
    'settings'=>[
        'accept'=>'.png, .jpg, .jpeg',
        'url'=>['/site/fileapi-upload']
    ]
]) ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-primary']) ?>
</div>

<?php ActiveForm::end() ?>
<?php
$this->registerJs('
   togglePolis($("#polis_exists").is(":checked") ? true : false);
$("#polis_exists").on({
    ifChecked: function() {
        togglePolis(true);
      
    },
    ifUnchecked: function() {
        togglePolis(false);
       
    }
});

function togglePolis(value) {
    $("#polis_oms_org").prop("required", value);
    $("#polis_oms_number").prop("required", value);
    $("#polis_attributes").css("display", (value ? "block" : "none"));
}

iCheckInit();
')
?>