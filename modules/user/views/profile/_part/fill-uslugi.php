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

if ($model->user_birth != '') {
    $tab_class1 = 'green';
    $tab_img1 = 'ok.png';
} else {
    $tab_class1 = 'grey';
    $tab_img1 = '1.png';
}

if ($model->study != '') {
    $tab_class2 = 'green';
    $tab_img2 = 'ok.png';
} else {
    $tab_class2 = 'grey';
    $tab_img2 = '1.png';
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
                    <img src="/img/icons/<?= $tab_img1 ?>" align="left" alt="1" border="0"/>
                    <p class="<?= $tab_class1 ?>">Личные <br/> данные</p>
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
                    <img src="/img/icons/3_active.png" align="left" alt="3" border="0"/>
                    <p class="red">Типы услуг <br/> данные</p>
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
        <div class="col-md-4">
            Выберите тип услуг, который вы сможете оказывать: <span style="color:red;">*</span><br/><br/>
            <?= $form->field($employee_consult, 'consult')->checkbox()->label(''); ?>
        </div>
        <div class="col-md-4">
            Установите стоимость первичной консультации: <br/>
            <?= $form->field($employee_consult, 'consult_price1')->textInput(['type' => 'number', 'min' => 1000, 'maxlength' => true, 'class' => 'form-control', 'placeholder' => 'мин 1000 руб.']) ?>
        </div>

        <div class="col-md-4">
            Установите стоимость последующих консультации: <br/>
            <?= $form->field($employee_consult, 'consult_price2')->textInput(['type' => 'number', 'min' => 1000, 'maxlength' => true, 'class' => 'form-control', 'placeholder' => 'мин 1000 руб.']) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <br/>
            <?= $form->field($employee_consult, 'consult_covid')->checkbox()->label(''); ?>
        </div>
        <div class="col-md-4">
        </div>
        <div class="col-md-4">
        </div>
    </div>

    <div class="row">
        <div class="col-md-4">
            <br/>
            <?= $form->field($employee_consult, 'consult_ekg')->checkbox()->label(''); ?>
        </div>
        <div class="col-md-4">
         </div>
        <div class="col-md-4">
         </div>
    </div>


    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= Html::a('&laquo; Назад', '/user/profile/fill?tab=work', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
        <div class="col-md-6">
            <div class="form-group pull-right">
                <?= Html::submitButton('Далее &raquo;', ['class' => 'btn btn-primary']) ?>
            </div>
        </div>
    </div>

    <br/><br/>


<?php ActiveForm::end() ?>