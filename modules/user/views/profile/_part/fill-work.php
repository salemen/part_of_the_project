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
use app\models\employee\Employee;
use app\models\employee\EmployeeDocument;
use app\models\employee\EmployeeCategory;
use app\models\employee\EmployeeDegree;
use app\models\employee\EmployeePosition;

use yii\jui\AutoComplete;


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
                    <img src="/img/icons/2_active.png" align="left" alt="2" border="0"/>
                    <p class="red">Образование<br/> и опыт работы</p>
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


<?php
//$listdata = Employee::find()->select(['study'])->where(['<>', 'study', ''])->groupBy('study')->orderBy('study')->asArray()->all();
$study_arr = array();
$study_arr['Среднее профессиональное образование'] = 'Среднее профессиональное образование';
$study_arr['Высшее образование - бакалавриат'] = 'Высшее образование - бакалавриат';
$study_arr['Высшее образование - специалитет'] = 'Высшее образование - специалитет';
$study_arr['Высшее образование - магистратура'] = 'Высшее образование - магистратура';
$study_arr['Высшее образование - подготовка кадров высшей квалификации'] = 'Высшее образование - подготовка кадров высшей квалификации';

$listdata = EmployeeDocument::find()->select(['empl_qual'])->where(['<>', 'empl_qual', ''])->groupBy('empl_qual')->orderBy('empl_qual')->asArray()->all();
$empl_qual_arr = array();
foreach ($listdata as $data) {
    if ($data['empl_qual'] != '') {
        $empl_qual_arr[] = trim($data['empl_qual']);
    }
}

$listdata = EmployeeCategory::find()->select(['empl_cat'])->where(['<>', 'empl_cat', ''])->groupBy('empl_cat')->orderBy('empl_cat')->asArray()->all();
$empl_cat_arr = array();
foreach ($listdata as $data) {
    if ($data['empl_cat'] != '') {
        $empl_cat_arr[] = trim($data['empl_cat']);
    }
}

$listdata = EmployeeCategory::find()->select(['empl_spec'])->where(['<>', 'empl_spec', ''])->groupBy('empl_spec')->orderBy('empl_spec')->asArray()->all();
$empl_spec_arr = array();
foreach ($listdata as $data) {
    if ($data['empl_spec'] != '') {
        $empl_spec_arr[] = trim($data['empl_spec']);
    }
}

$listdata = EmployeeDegree::find()->select(['empl_degree'])->where(['<>', 'empl_degree', ''])->groupBy('empl_degree')->orderBy('empl_degree')->asArray()->all();
$empl_degree_arr = array();
foreach ($listdata as $data) {
    if ($data['empl_degree'] != '') {
        $empl_degree_arr[] = trim($data['empl_degree']);
    }
}

$listdata = EmployeeDegree::find()->select(['empl_rank'])->where(['<>', 'empl_rank', ''])->groupBy('empl_rank')->orderBy('empl_rank')->asArray()->all();
$empl_rank_arr = array();
foreach ($listdata as $data) {
    if ($data['empl_rank'] != '') {
        $empl_rank_arr[] = trim($data['empl_rank']);
    }
}

$empl_pos = array();
$empl_positions = EmployeePosition::find()
    ->andWhere(['is_doctor' => 1])
    ->orderBy('empl_pos')
    ->distinct('empl_pos')
    ->all();

foreach ($empl_positions as $position) {
    if (!in_array($position['empl_pos'], $empl_pos)) {
        $empl_pos[] = $position['empl_pos'];
    }
}


?>

<?php $form = ActiveForm::begin() ?>

    <div class="row">
        <div class="col-md-3">
            <?= $form->field($model, 'study_type')->radioList([0 => 'Среднее', 1 => 'Высшее'], [
                'style' => 'display:block',
                'separator' => ' <br/>',
                'required' => true
            ])->label('Образование <span style="color:red">*</span>'); ?>


            <?= $form->field($model, 'study')
                ->dropDownList(
                    $study_arr
                )->label('Образование <span style="color:red">*</span>'); ?>


        </div>
        <div class="col-md-5">
            <?= $form->field($employee_document, 'empl_qual')->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $empl_qual_arr,
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Необязательно'
                ]
            ]);
            ?>
            <?= $form->field($employee_category, 'empl_cat')->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $empl_cat_arr,
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Необязательно'
                ]
            ]);
            ?>
            <?= $form->field($employee_degree, 'empl_degree')->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $empl_degree_arr,
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Необязательно'
                ]
            ]);
            ?>
        </div>

        <div class="col-md-4">
            <?= $form->field($employee_degree, 'empl_rank')->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $empl_rank_arr,
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => 'Необязательно'
                ]
            ]);
            ?>

            <?= $form->field($employee_category, 'empl_spec')->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $empl_spec_arr,
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'required' => true
                ]
            ])->label('Специализация <span style="color:red">*</span>');
            ?>

            <?= $form->field($employee_position, 'empl_pos')->widget(
                AutoComplete::className(), [
                'clientOptions' => [
                    'source' => $empl_pos,
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => '',
                    'required' => true
                ]
            ])->label('Специальность <span style="color:red">*</span>');
            ?>

            <?= $form->field($employee_document, 'doc_scan')->widget(FileAPI::className(), [
                'settings' => [
                    'accept' => '.zip',
                    'url' => ['/site/fileapi-upload']
                ]
            ])->label('Загрузите сканы подтверждающих документов в архиве формата ZIP, для того, чтоб мы могли их проверить <span style="color:red">*</span>') ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <div class="form-group">
                <?= Html::a('&laquo; Назад', '/user/profile/fill', ['class' => 'btn btn-primary']) ?>
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