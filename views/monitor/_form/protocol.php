<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\monitor\MonitorProtocolOrvi;

$this->title = 'Мониторинг ОРВИ/COVID-19';
$this->params['breadcrumbs'][] = ['label'=>'Ковид онлайн', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$values = MonitorProtocolOrvi::getValues();
?>

<?php $form = ActiveForm::begin([
    'id'=>'monitor-orvi-form',
    'enableAjaxValidation'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false
]) ?>

    <div class="row">
        <div class="col-md-12">
            <div class="box box-body box-primary">
                <?php if($model->passport->reason == 60 ) {
                    echo $form->field($model, 'covid')->widget(Select2::className(), [
                        'data'=>[1=>'Положительный'],
                        'options'=>[
                            'class'=>'form-control',
                        ]
                    ])->label('Тест на COVID19');
                }else {
                    echo $form->field($model, 'covid')->widget(Select2::className(), [
                        'data' => [1 => 'Положительный', 0 => 'Отрицательный', 2 => 'Не сдавал'],
                        'options' => [
                            'class' => 'form-control',
                            'placeholder' => 'Выберите значение'
                        ]
                    ])->label('Тест на COVID19');
                }?>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_temp')->widget(MaskedInput::className(), ['mask'=>'99.9']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_kash')->textInput(['class'=>'form-control p_kash-input', 'min'=>0, 'max'=>60, 'type'=>'number']) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_kash_type')->widget(Select2::className(), [
                            'data'=>$values['p_kash_type'],
                            'disabled'=>true,
                            'options'=>[
                                'class'=>'form-control p_kash_type-input',
                                'placeholder'=>'Укажите характер кашля'
                            ]
                        ])->label($model->getAttributeLabel('p_kash_type') . ' ' . Html::a('Как определить?', '#', ['class'=>'p_kash_type-modal', 'style'=>'text-decoration: underline;'])) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_odishka')->widget(Select2::className(), [
                            'data'=>$values['p_odishka'],
                        ])->label($model->getAttributeLabel('p_odishka') . ' ' . Html::a('Как определить?', '#', ['class'=>'p_odishka-modal', 'style'=>'text-decoration: underline;'])) ?>
                    </div>

                    <div class="col-md-4">
                        <?= $form->field($model, 'p_chast')->textInput([
                            'placeholder'=>'Введите значение от 16 до 60',
                            'type'=>'number'
                        ])->label($model->getAttributeLabel('p_chast') . ' ' . Html::a('Как измерить?', '#', ['class'=>'p_chast-modal', 'style'=>'text-decoration: underline;'])) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_limf')->widget(Select2::className(), [
                            'data'=>$values['p_limf'],
                        ])->label($model->getAttributeLabel('p_limf') . ' ' . Html::a('Как определить?', '#', ['class'=>'p_limf-modal', 'style'=>'text-decoration: underline;'])) ?>

                    </div>
                </div>
                <div class="row">
                    <div class="col-md-3">
                        <?= $form->field($model, 'p_tyazh')->widget(Select2::className(), ['data'=>$values['p_tyazh']]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'p_toshn')->widget(Select2::className(), ['data'=>$values['p_toshn']]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'p_slab')->widget(Select2::className(), ['data'=>$values['p_slab']]) ?>
                    </div>
                    <div class="col-md-3">
                        <?= $form->field($model, 'p_diarea')->textInput(['type'=>'number', 'value'=>0]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_bolmysh')->widget(Select2::className(), ['data'=>$values['p_bolmysh']]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_bolgorlo')->widget(Select2::className(), ['data'=>$values['p_bolgorlo']]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_bolgolova')->widget(Select2::className(), ['data'=>$values['p_bolgolova']]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_lek_zhar')->widget(Select2::className(), ['data'=>$values['p_lek_vir']]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_lek_vir')->widget(Select2::className(), ['data'=>$values['p_lek_vir']]) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'p_lek_antib')->widget(Select2::className(), ['data'=>$values['p_lek_antib']]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <?= $form->field($model, 'p_pulsmetr')->textInput(['placeholder'=>'Введите показания (%)', 'type'=>'number']) ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'p_zapah')->widget(Select2::className(), ['data'=>$values['p_zapah'], 'options'=>['placeholder'=>'']]) ?>
                    </div>
                    <div class="col-md-12">
                        <?= $form->field($model, 'p_feel')->widget(Select2::className(), ['data'=>$values['p_feel'], 'options'=>['placeholder'=>'']]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'complain')->textarea(['placeholder'=>'Есть еще жалобы ?']) ?>
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
$(document).on("change", ".p_kash-input", function() {
    if ($(this).val() == "" || $(this).val() == 0) {
        $(".p_kash_type-input").prop("disabled", true);
    } else {
        $(".p_kash_type-input").prop("disabled", false);
    }
});
$(document).on("click", ".p_chast-modal", function (e) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-primary",
                text: "ok"                
            }
        },
        content: function () {
            var self = this;
            return $.ajax({
                method: "post",
                url: "/help/chast-dih"
            }).done(function (result) {
                self.setContent(result);
            });
        },
        theme: "modern",
        title: false
    });
    e.preventDefault();
});
$(document).on("click", ".p_limf-modal", function (e) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-primary",
                text: "ok"                
            }
        },
        columnClass: "col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1",
        content: function () {
            var self = this;
            
            return $.ajax({
                method: "post",
                url: "/help/limfo"
            }).done(function (result) {
                self.setContent(result);
            });
        },        
        theme: "modern",
        title: false
    });
    e.preventDefault();
});
$(document).on("click", ".p_odishka-modal", function (e) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-primary",
                text: "ok"                
            }
        },
        columnClass: "col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1",
        content: function () {
            var self = this;
            
            return $.ajax({
                method: "post",
                url: "/help/odishka"
            }).done(function (result) {
                self.setContent(result);
            });
        },        
        theme: "modern",
        title: false
    });
    e.preventDefault();
});
$(document).on("click", ".p_kash_type-modal", function (e) {
    $.alert({
        buttons: {
            ok: {
                btnClass: "btn-primary",
                text: "ok"                
            }
        },
        columnClass: "col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2 col-xs-10 col-xs-offset-1",
        content: function () {
            var self = this;
            
            return $.ajax({
                method: "post",
                url: "/help/kashel"
            }).done(function (result) {
                self.setContent(result);
            });
        },        
        theme: "modern",
        title: false
    });
    e.preventDefault();
});
');