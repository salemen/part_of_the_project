<?php
use yii\widgets\ActiveForm;
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use vova07\fileapi\Widget as FileAPI;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;


if ($model->study != '') {
    $tab_class2 = 'green';
    $tab_img2 = 'ok.png';
} else {
    $tab_class2 = 'grey';
    $tab_img2 = '1.png';
}

if ($employee_consult->consult_ekg == 1 || $employee_consult->consult_covid == 1 || $employee_consult->consult == 1) {
    $tab_class3 = 'green';
    $tab_img3 = 'ok.png';
} else {
    $tab_class3 = 'grey';
    $tab_img3 = '3.png';
}

if ($employee_payment->inn != '') {
    $tab_class4 = 'green';
    $tab_img4 = 'ok.png';
} else {
    $tab_class4 = 'grey';
    $tab_img4 = '4.png';
}

?>
    <div class="hidden-xs hidden-md">
        <br/>
        <div class="row profile-headers">
            <div class="col-md-3">
                <a href="/user/profile/fill">
                    <img src="/img/icons/1_active.png" align="left" alt="1" border="0"/>
                    <p class="red"">Личные <br/> данные</p>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/user/profile/fill?tab=work">
                    <img src="/img/icons/<?= $tab_img2 ?>" align="left" alt="2" border="0"/>
                    <p class="<?= $tab_class2 ?>">Образование<br/> и опыт работы</p>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/user/profile/fill?tab=uslugi">
                    <img src="/img/icons/<?= $tab_img3 ?>" align="left" alt="3" border="0"/>
                    <p class="<?= $tab_class3 ?>">Типы услуг <br/> данные</p>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/user/profile/fill?tab=payment">
                    <img src="/img/icons/<?= $tab_img4 ?>" align="left" alt="4" border="0"/>
                    <p class="<?= $tab_class4 ?>">Способ <br/> оплаты</p>
                </a>
            </div>
        </div>
        <hr/>
    </div>

<?php $form = ActiveForm::begin() ?>
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => 'true']) ?>

            <?= $form->field($model, 'photo')->widget(FileAPI::className(), [
                'crop' => true,
                'cropResizeWidth' => 250,
                'cropResizeHeight' => 300,
                'jcropSettings' => [
                    'aspectRatio' => 0.83,
                    'bgColor' => '#ffffff',
                    'maxSize' => [500, 600],
                    'minSize' => [100, 120],
                    'keySupport' => false,
                    'selection' => '100%'
                ],
                'settings' => [
                    'accept' => '.png, .jpg, .jpeg',
                    'url' => ['/site/fileapi-upload']
                ]
            ]) ?>

            <?php

            if ($model->pay_type == 0) {
                $dogovor = 'dogovor.php';
            }
            if ($model->pay_type == 1 || $model->pay_type == 2) {
                $dogovor = 'dogovorip.php';
            }

            if ($employee_payment->dogovor == 1) :?>
                <a href="/user/docs" class="btn btn-success">Скачать договор</a>
                <iframe src="/tcpdf/dogovor/<?= $dogovor ?>?id=<?= $model->id ?>"
                        style="display:none;width:0;height:0;"></iframe>
            <?php endif; ?>

        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'user_birth')->widget(DatePicker::className(), [
                'options' => ['placeholder' => '01.01.1970', 'required' => true],
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy',
                    'onSelectTime' => false,
                    'datesDisabled' => '-18y',
                    'autoclose' => true,
                    'endDate' => '-18y',

                ]
            ])->label('Дата рождения <span style="color:red">*</span>') ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => 'true']) ?>
            <?= $form->field($model, 'phone_work')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'form-control', 'disabled' => 'true']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true, 'class' => 'form-control', 'required' => true])->label('Город <span style="color:red">*</span>') ?>

            <?= $form->field($model, 'sex')
                ->dropDownList(
                    [0 => 'женский', 1 => 'мужской']
                )->label('Пол <span style="color:red">*</span>'); ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'snils')->widget(MaskedInput::className(), [
                'mask' => '999-999-999 99',
                'options' => [
                    'class' => 'form-control', 'required' => true,
                ]
            ])->label('СНИЛС <span style="color:red">*</span>') ?>


            <?= $form->field($model, 'pay_type')->radioList([1 => 'Самозанятый (с консультации 60%)', 2 => 'ИП (с консультации 60%)', 0 => 'Отсутсвует (с консультации 30%)'], [
                'style' => 'display:block',
                'separator' => ' <br/>'
            ])->label('Тип плательщика <span style="color:red">*</span>'); ?>


        </div>

    </div>

    <div class="row">
        <div class="col-md-6">
        </div>
        <div class="col-md-6">
            <div class="form-group pull-right">
                <?= Html::submitButton('Далее  &raquo;', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>
    <br/><br/>

<?php ActiveForm::end() ?>