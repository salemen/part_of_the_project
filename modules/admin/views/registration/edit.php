<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use vova07\fileapi\Widget as FileAPI;
use kartik\date\DatePicker;
use yii\jui\AutoComplete;
use app\models\employee\Employee;
use app\models\employee\EmployeeDocument;
use app\models\employee\EmployeeCategory;
use app\models\employee\EmployeeDegree;
use app\models\user\UserDocs;
use yii\widgets\MaskedInput;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label' => 'Менеджер: Консультанты', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin() ?>

<div class="tab-content">
    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'fullname')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

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

            if ($employee_payment->inn == 1) :?>
                <?php
                $user_doc = UserDocs::findOne(['user_id' => $model->id, 'doc_ext' => 'pdf', 'doc_name' => 'Договор-оферта']);
                $link = '';
                if ($user_doc) {
                    $link = '/uploads/' . $user_doc->doc_file;
                }
                ?>
                <a href="<?= $link ?>" class="btn btn-success" target="_blank">Скачать договор</a>
                <iframe src="/tcpdf/dogovor/<?= $dogovor ?>?id=<?= $model->id ?>"
                        style="display:none;width:0;height:0;"></iframe>
            <?php endif; ?>
            <br/><br/>
            <?= $form->field($model, 'activity')->textInput(['maxlength' => true, 'class' => 'form-control hidden', 'hidden' => true]) ?>
            <?= \yii\bootstrap\ToggleButtonGroup::widget([
                'id' => 'activity-toggle',
                'name' => 'activity_toggle',
                'type' => 'radio',
                'items' => [
                    -1 => 'нет',
                    1 => 'да'
                ],
                'labelOptions' => [
                    'class' => ['btn', 'btn-primary activityButton'],
                    'wrapInput' => true,
                    'onclick' => '  
            document.getElementById("employee-activity").value= this.getElementsByTagName("input")[0].value;'
                ],
                'value' => [$model->activity]
            ]); ?>

            <?php
            $user_doc = UserDocs::findOne(['user_id' => $model->id, 'doc_ext' => 'pdf', 'doc_name' => 'Договор-оферта']);
            $link = '';
            if ($user_doc) {
                $link = '/uploads/' . $user_doc->doc_file;

                echo '&nbsp; &nbsp; <a href="' . $link . '" target="_blank" class="btn btn-success">Скачать договор</a>';
            }
            ?>

        </div>
        <div class="col-md-3">

            <?= $form->field($model, 'user_birth')->widget(DatePicker::className(), [
                'pluginOptions' => [
                    'autoclose' => true,
                    'format' => 'dd.mm.yyyy',
                    'onSelectTime' => false,
                    'datesDisabled' => '-18y',
                    'autoclose' => true,
                    'endDate' => '-18y'

                ]
            ]) ?>

            <?= $form->field($model, 'phone')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            <?= $form->field($model, 'phone_work')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>
        </div>

        <div class="col-md-2">
            <?= $form->field($model, 'city')->textInput(['maxlength' => true, 'class' => 'form-control']) ?>

            <?= $form->field($model, 'sex')
                ->dropDownList(
                    [0 => 'женский', 1 => 'мужской']
                ); ?>

        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'snils')->widget(MaskedInput::className(), [
                'mask' => '999-999-999 99',
                'options' => [
                    'class' => 'form-control', 'required' => true,
                ]
            ])->label('СНИЛС') ?>


            <?= $form->field($model, 'pay_type')->radioList([1 => 'Самозанятый (с консультации 60%)', 2 => 'ИП (с консультации 60%)', 0 => 'Отсутсвует (с консультации 30%)'], [
                'style' => 'display:block',
                'separator' => ' <br/>'
            ]); ?>
        </div>
    </div>

    <hr/>


    <?php if ($employee_payment->inn): ?>
        <div class="row">
            <div class="col-md-6">
                <h3>Реквизиты</h3>
                <?= $form->field($employee_payment, 'name')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'ИП Иванов Иван Иванович']); ?>
                <?= $form->field($employee_payment, 'inn')->textInput(['maxlength' => 12, 'minlength' => 10, 'class' => 'form-control', 'placeholder' => '10 цифр']) ?>
                <?= $form->field($employee_payment, 'bank')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Банк']); ?>
                <?= $form->field($employee_payment, 'rbill')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите данные']); ?>
                <?= $form->field($employee_payment, 'bik')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите данные']); ?>
                <?= $form->field($employee_payment, 'kbill')->textInput(['maxlength' => true, 'class' => 'form-control', 'placeholder' => 'Введите данные']); ?>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
    </div>
</div>

<?php ActiveForm::end() ?>

