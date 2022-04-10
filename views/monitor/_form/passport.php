<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\data\Department;
use app\models\monitor\MonitorPassport;
use kartik\depdrop\DepDrop;
use app\models\oms\Oms;
use app\models\user\UserData;
use kartik\date\DatePicker;

$this->title = 'Паспорт наблюдения';
$this->params['breadcrumbs'][] = ['label'=>'Ковид онлайн', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;


$values = MonitorPassport::getValues();
?>

<?php $form = ActiveForm::begin([
    'id'=>'monitor-passport-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

    <div class="row">
        <div class="col-md-12">
            <h1 class="anketa-header">Анкета</h1>
            <div class="box box-body box-primary">

                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'user_f')->textInput(['maxlength'=>true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'user_i')->textInput(['maxlength'=>true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'user_o')->textInput(['maxlength'=>true]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'user_birth')->widget(DatePicker::className(), [
                            'value'=>function($model) {
                                return $model->user_birth;
                            },
                            'name' => 'dp_1',
                            'type' => DatePicker::TYPE_INPUT,
                            'pluginOptions'=>[
                                'autoclose' => true,
                                'format' => 'dd.mm.yyyy',
                                'endDate'=> date("d.m.Y") > date("d.m.Y")  ? date("d.m.Y",microtime(true)-(60*60*24)) : date("d.m.Y"),
                                'orientation' => 'bottom',
                            ]
                        ])->label('Дата рождения*',['class' => 'someClass']) ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <?php if($model->patient){ ?>
                            <?= $form->field($model->patient, 'sex')->widget(Select2::className(), [
                                'data'=>[1=>'Мужской', 0=>'Женский'],
                                'options'=>[
                                    'class'=>'form-control'
                                ]
                            ])->label('Пол*',['class' => 'someClass']) ?>
                        <?php } elseif($model->employee) { ?>
                            <?= $form->field($model->employee, 'sex')->widget(Select2::className(), [
                                'data'=>[1=>'Мужской', 0=>'Женский'],
                                'options'=>[
                                    'class'=>'form-control'
                                ]
                            ])->label('Пол*',['class' => 'someClass']) ?>
                        <?php } ?>
                    </div>
                    <div class="col-md-3">

                        <?= $form->field($model, 'city')->widget(Select2::className(), [
                            'initValueText'=>($model->city) ? $model->city : null,
                            'id'=>'cat-id',
                            'options'=>['placeholder'=>'Укажите город'],
                            'pluginOptions'=>[
                                'ajax'=>[
                                    'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                                    'dataType'=>'json',
                                    'delay'=>250,
                                    'url'=>Url::to(['/data/city', 'keytext'=>true])
                                ],
                                'minimumInputLength'=>3,
                                'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                                'templateSelection'=>new JsExpression('function (data) { return data.text; }')
                            ]
                        ]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'address')->textInput(['maxlength'=>true]) ?>
                    </div>
                </div>

                <?= $form->field($model, 'reason')->widget(Select2::className(), [
                    'data'=>$values['reason'],
                    'options'=>[
                        'placeholder'=>'Выберите причину'
                    ]
                ]) ?>

                <?= $form->field($model, 'sicks')->widget(Select2::className(), [
                    'data'=>[
                        'Бронхиальная астма'=>'Бронхиальная астма',
                        'Гипертоническая болезнь'=>'Гипертоническая болезнь',
                        'Онкология'=>'Онкология',
                        'Сахарный диабет'=>'Сахарный диабет',
                        'СПИД'=>'СПИД'
                    ],
                    'options'=>[
                        'placeholder'=>'Выберите сопутствующие заболевания (если нет - ничего выбирать не нужно)',
                        'multiple'=>true
                    ]
                ]) ?>


                <div class="col-md-12" style="padding-bottom: 10px; padding-right: 5px;">
                    <label for="radio-1">Вы прикреплены к медицинской организации ЦСМ-Санталь?&nbsp;&nbsp;&nbsp;</label>
                    <input id="polis_exists" name="motive" type="checkbox">
                </div>
                <div id="polis_attributes" class="row">
                    <div class="col-md-4">
                        <?php if(!empty($model->patient->data)) {
                            echo $form->field($model->patient->data, 'clinic')->dropDownList($items);
                        }elseif(!empty($model->employee->data)) {
                            echo $form->field($model->employee->data, 'clinic')->dropDownList($items);
                        }else {
                            echo $form->field($model, 'clinic')->dropDownList(
                                ArrayHelper::map(Department::find()->select(['id', "CONCAT(name, '  ', address ) AS name"])->where(['is_santal' => 1, 'status' => 10])->orderBy('name')->all(), 'name', 'name') + ['другая' => 'Другая (указать вручную)'],
                                [
                                    'class' => 'form-control monitor-clinic',
                                    'prompt' => 'Выберите поликлинику, к которой Вы прикреплены'
                                ]
                            );
                        }?>

                    </div>
                    <div class="col-md-4">
                        <?php if(!empty($model->patient->data)) {
                            echo $form->field($model->patient->data, 'polis_oms_org')->dropDownList(
                                ArrayHelper::map(Oms::find()->all(), 'oms', 'oms'),
                                [ 'prompt' => 'Выберите страховую компанию']
                            );
                        }elseif(!empty($model->employee->data)) {
                            echo $form->field($model->employee->data, 'polis_oms_org')->dropDownList(
                                ArrayHelper::map(Oms::find()->all(), 'oms', 'oms'),
                                [ 'prompt' => 'Выберите страховую компанию']

                            );
                        }else{
                            echo $form->field($user_data, 'polis_oms_org')->dropDownList($items2,
                                [ 'prompt' => 'Выберите страховую компанию'])
                                ->label('Страховая компания ОМС*', ['class' => 'someClass']);
                        }
                        ?>

                    </div>
                    <div class="col-md-4">
                        <?php if(!empty($model->patient->data)) {
                            echo $form->field($model->patient->data, 'polis_oms_number')->widget(MaskedInput::className(), [
                                'mask'=>'9999999999999999',
                                'options'=>[
                                    'class'=>'form-control'
                                ]
                            ]);
                        }elseif(!empty($model->employee->data)){
                            echo $form->field($model->employee->data,  'polis_oms_number')->widget(MaskedInput::className(), [
                                'mask'=>'9999999999999999',
                                'options'=>[
                                    'class'=>'form-control'
                                ]
                            ]);
                        }else{
                            echo $form->field($user_data, 'polis_oms_number')->widget(MaskedInput::className(), [
                                'mask'=>'9999999999999999',
                                'options'=>[
                                    'class'=>'form-control'
                                ]
                            ]);
                        }?>
                    </div>

                </div>

                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'period_start')->textInput(['disabled'=>true]) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'period_end')->textInput(['disabled'=>true]) ?>
                    </div>
                </div>

                <div class="form-group" style="margin-top: 20px; text-align: center">
                    <?= Html::submitButton('Сохранить', ['class'=>'btn btn-lg btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
$(".monitor-clinic").on("change", function() {
    if ($(this).val() === "другая") {
        var id = $(this).attr("id");
        var name = $(this).attr("name");
        $(this).replaceWith("<input type=\"text\" id=" + id + " name=" + name + " class=\"form-control\">");
    }
}); 

togglePolis($("#polis_exists").is(":checked") ? true : false);
$("#polis_exists").on({
    ifChecked: function() {
        togglePolis(true);
       togglePolis1(false);
    },
    ifUnchecked: function() {
        togglePolis(false);
        togglePolis1(true);
    }
});


function togglePolis(value) {
    $("#polis_oms_org").prop("required", value);
    $("#polis_oms_number").prop("required", value);
    $("#polis_attributes").css("display", (value ? "block" : "none"));
}


function togglePolis1(value) {
    $("#address").prop("required", value);
    $("#price").css("display", (value ? "block" : "none"));
}

iCheckInit();
');

