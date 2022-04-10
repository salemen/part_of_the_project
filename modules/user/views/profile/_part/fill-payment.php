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

if ($employee_consult->consult_ekg == 1 || $employee_consult->consult_covid == 1 || $employee_consult->consult == 1) {
    $tab_class3 = 'green';
    $tab_img3 = 'ok.png';
} else {
    $tab_class3 = 'grey';
    $tab_img3 = '3.png';
}

if ($model->study != '') {
    $tab_class2 = 'green';
    $tab_img2 = 'ok.png';
} else {
    $tab_class2 = 'grey';
    $tab_img2 = '1.png';
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
                    <img src="/img/icons/<?= $tab_img3 ?>" align="left" alt="3" border="0"/>
                    <p class="<?= $tab_class3 ?>">Типы услуг <br/> данные</p>
                </a>
            </div>
            <div class="col-md-3">
                <a href="/user/profile/fill?tab=payment">
                    <img src="/img/icons/4_active.png" align="left" alt="4" border="0"/>
                    <p class="red">Способ <br/> оплаты</p>
                </a>
            </div>
        </div>
        <hr/>
    </div>

<?php $form = ActiveForm::begin() ?>

    <div class="row">
        <div class="col-md-6">
            Заполните активные поля для формирования договора и дальнейщих выплат: <br/><br/>
            <?= $form->field($employee_payment, 'name')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'ИП Иванов Иван Иванович']); ?>
            <?= $form->field($employee_payment, 'inn')->textInput(['maxlength' => 12, 'minlength' => 10, 'class' => 'form-control', 'placeholder' => '10 цифр']) ?>
            <?= $form->field($employee_payment, 'bank')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Банк']); ?>
            <?= $form->field($employee_payment, 'rbill')->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите данные']); ?>
            <?= $form->field($employee_payment, 'bik')->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите данные']); ?>
            <?= $form->field($employee_payment, 'kbill')->textInput(['type' => 'number', 'maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите данные']); ?>
            <?= $form->field($employee_payment, 'agree')->checkbox(); ?>
            <?= $form->field($employee_payment, 'dogovor')->checkbox(); ?>
        </div>
        <div class="col-md-6">
        </div>
    </div>


    <div class="form-group">
        <?= Html::a('&laquo; Назад', '/user/profile/fill?tab=uslugi', ['class' => 'btn btn-primary']) ?> &nbsp;
        <?= Html::submitButton('Завершить регистрацию', ['class' => 'btn btn-primary']) ?>
    </div>


<?php ActiveForm::end() ?>