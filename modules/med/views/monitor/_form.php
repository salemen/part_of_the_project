<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\widgets\MaskedInput;
use kartik\depdrop\DepDrop;
use app\models\data\Department;
use app\models\oms\Oms;


$this->params['breadcrumbs'][] = ['label'=>'Мониторинг', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'action' => 'call',
    'id'=>'proposal-form'
]) ?>

<?php if(isset($model->patient)): ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model->patient, 'fullname')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model->patient, 'user_birth')->textInput(['maxlength'=>true]) ?>

        </div>
        <div class="col-md-3">
            <?= $form->field($model->patient->data, 'address')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model->patient, 'phone')->textInput(['maxlength'=>true]) ?>
        </div>
    </div>

    <?php  if (!empty($model->patient->passport->protocols->complain)){ ?>
        <div class="row">

            <?php
            $model->patient->passport->protocols->complain = $model->patient->passport->protocols->complain.' Температура-'.$model->patient->passport->protocols->p_temp.', Боль в горле-'.$model->patient->passport->protocols->p_bolgorlo.
                ', Одышка-'.$model->patient->passport->protocols->p_odishka.', Потеря обоняния-'.$model->patient->passport->protocols->p_zapah.', Кашель(раз в сутки)-'.$model->patient->passport->protocols->p_kash.
                ', Характер кашля-'.$model->patient->passport->protocols->p_kash_type.', Тяжесть в груди-'.$model->patient->passport->protocols->p_tyazh.', Самочувствие-'.$model->patient->passport->protocols->p_feel.
                ', Диарея(раз в сутки)-'.$model->patient->passport->protocols->p_diarea;
            ?>

            <div class="col-md-12">
                <?= $form->field($model->patient->passport->protocols, 'complain')->textarea(['maxlength'=>true]) ?>
            </div>
        </div>
    <?php } else { ?>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_temp')->textInput(['maxlength'=>true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_bolgorlo')->textInput(['maxlength'=>true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_odishka')->textInput(['maxlength'=>true]) ?>
            </div>
        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_zapah')->textInput(['maxlength'=>true]) ?>
            </div>
            <?php if($model->patient->passport->protocols->p_kash): ?>
                <div class="col-md-4">
                    <?= $form->field($model->patient->passport->protocols, 'p_kash')->textInput(['maxlength'=>true]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model->patient->passport->protocols, 'p_kash_type')->textInput(['maxlength'=>true]) ?>
                </div>
            <?php endif; ?>
            <?php if(!$model->patient->passport->protocols->p_kash): ?>
                <div class="col-md-4">
                    <?= $form->field($model->patient->passport->protocols, 'p_slab')->textInput(['maxlength'=>true]) ?>
                </div>
            <?php endif; ?>

        </div>
        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_feel')->textInput(['maxlength'=>true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_toshn')->textInput(['maxlength'=>true]) ?>
            </div>
            <div class="col-md-4">
                <?= $form->field($model->patient->passport->protocols, 'p_diarea')->textInput(['maxlength'=>true]) ?>
            </div>
        </div>
    <?php } ?>

    <div class="row">
        <div class="col-md-4">
            <?php if(empty($model->patient->data->polis_oms_org)){
                echo $form->field($model->patient->data, 'polis_oms_org')->dropDownList(
                    ArrayHelper::map(Oms::find()->select(['oms'])->orderBy('oms')->all(), 'oms', 'oms') + ['другая'=>'Другая (указать вручную)'],
                    [
                        'class'=>'form-control',
                        'prompt' =>'Выберите организацию'
                    ]
                );
            }else{
                echo $form->field($model->patient->data, 'polis_oms_org')->textInput(['maxlength' => true]);
            }?>
        </div>
        <div class="col-md-4">
            <?php echo $form->field($model->patient->data, 'polis_oms_number')->widget(MaskedInput::className(), [
                'mask'=>'9999999999999999',
                'options'=>[
                    'class'=>'form-control'
                ]
            ])->label('Полис ОМС');
            ?>
        </div>
        <div class="col-md-4">
            <?php if(empty($model->patient->data->clinic)){
                echo $form->field($model->patient->data, 'clinic')->widget(Select2::className(), [
                    'data'=>ArrayHelper::map(Department::find()->where(['is_santal'=>true, 'status'=>10])->orderBy('name')->all(), 'id', function($item) {
                        return $item->name . ' (' . $item->address . ')';
                    })
                ]);
            } else {
                echo $form->field($model->patient->data, 'clinic')->textInput(['maxlength'=>true]);
            }?>

        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'period_start')->widget(MaskedInput::className(), ['mask'=>'99.99.9999']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'period_end')->widget(MaskedInput::className(), ['mask'=>'99.99.9999']) ?>
        </div>
    </div>

    </div>
<?php endif; ?>
<?php if(isset($model->employee)): ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model->employee, 'fullname')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model->employee, 'user_birth')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model->employee->data, 'address')->textInput(['maxlength'=>true]) ?>
        </div>
        <div class="col-md-3">
            <?= $form->field($model->employee, 'phone')->textInput(['maxlength'=>true]) ?>
        </div>
    </div>

    <div class="row">

        <?php
        $model->employee->passport->protocols->complain = $model->employee->passport->protocols->complain.' Температура- '.$model->employee->passport->protocols->p_temp.', Боль в горле- '.$model->employee->passport->protocols->p_bolgorlo.
            ', Одышка- '.$model->employee->passport->protocols->p_odishka.', Потеря обоняния- '.$model->employee->passport->protocols->p_zapah.', Кашель(раз в сутки)- '.$model->employee->passport->protocols->p_kash.
            ', Характер кашля- '.$model->employee->passport->protocols->p_kash_type.', Тяжесть в груди- '.$model->employee->passport->protocols->p_tyazh.', Самочувствие- '.$model->employee->passport->protocols->p_feel.
            ', Диарея(раз в сутки)- '.$model->employee->passport->protocols->p_diarea;
        ?>

        <div class="col-md-12">
            <?= $form->field($model->employee->passport->protocols, 'complain')->textarea() ?>
        </div>

    </div>
    <div class="row">
        <div class="col-md-12">
            <?= $form->field($userProposal, 'payment')->checkbox(['id'=>'is_payment','checked'=>false]) ?>

            <div id="payment_attributes">
                <?= $form->field($userProposal, 'cost')->textInput(['id'=>'cost', 'maxlength'=>true]) ?>
            </div>

            <?php if (isset($model->employee->data->clinic)):?>
                <div class="col-md-4">
                    <?= $form->field($model->employee->data, 'clinic')->textInput() ?>
                </div>
            <?php endif; ?>
            <?php if (!isset($model->employee->data->clinic)):?>
                <div class="col-md-4">
                    <?= $form->field($model->employee->data, 'clinic')->dropDownList(
                        ArrayHelper::map(Department::find()->select(['id',"CONCAT(name, '  ', address ) AS name"])->where(['is_santal'=>1, 'status'=>10])->orderBy('name')->all(), 'name', 'name') + ['другая'=>'Другая (указать вручную)'],
                        [
                            'class'=>'form-control monitor-clinic',
                        ]
                    ) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($model->employee->data->polis_oms_org)):?>
                <div class="col-md-4">
                    <?= $form->field($model->employee->data, 'polis_oms_org')->textInput() ?>
                </div>
            <?php endif; ?>
            <?php if (!isset($model->employee->data->polis_oms_org)):?>
                <div class="col-md-4">
                    <?= $form->field($model->employee->data, 'polis_oms_org')->dropDownList($items2)
                        ->label('Страховая компания ОМС*',['class' => 'someClass'])?>
                </div>
            <?php endif; ?>
            <?php if (!isset($model->employee->data->polis_oms_number)):?>
                <div class="col-md-4">
                    <?= $form->field($model->employee->data, 'polis_oms_number')->widget(MaskedInput::className(), [
                        'mask'=>'9999999999999999',
                        'options'=>[
                            'class'=>'form-control'
                        ]
                    ]) ?>
                </div>
            <?php endif; ?>
            <?php if (isset($model->employee->data->polis_oms_number)):?>
                <div class="col-md-4">
                    <?= $form->field($model->employee->data, 'polis_oms_number')->textInput()?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'period_start')->widget(MaskedInput::className(), ['mask'=>'99.99.9999']) ?>
        </div>
        <div class="col-md-6">
            <?= $form->field($model, 'period_end')->widget(MaskedInput::className(), ['mask'=>'99.99.9999']) ?>
        </div>
    </div>

    </div>

<?php endif; ?>
    <div class="form-group">
        <?= Html::submitButton('Отправить заявку', ['class'=> 'btn btn-success']) ?>
        <?= Html::a('Отмена', ['index'], ['class'=>'btn btn-danger']) ?>
    </div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();
toggleInput($("#is_payment").is(":checked") ? true : false);
$("#is_payment").on({
    ifChecked: function() {
        toggleInput(true);
    },
    ifUnchecked: function() {
        toggleInput(false);
    }
});

function toggleInput(value) {
    $("#cost").prop("required", value);
    $("#cost").val(value ? $("#cost").val() : "");
    $("#payment_attributes").css("display", (value ? "block" : "none"));
}
');
