<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\data\Department;
use app\models\data\Organization;
use app\models\employee\EmployeeAdvisor;
use app\models\employee\EmployeeDocument;
use app\models\employee\EmployeePosition;


$user = Yii::$app->user;

if (!$user->isGuest) {
    $city = $user->identity->city;
    if ($model->empl_city == '' && Organization::find()->where(['city' => $city])->exists()) {
        $model->empl_city = $city;
    }
}

$advisor = EmployeeAdvisor::find()->select('employee_id')->asArray()->all();

$empl_qual = EmployeeDocument::find()
    ->where(['doc_type' => 'Сертификат'])
    ->andWhere(['IN', 'employee_id', $advisor])
    ->orderBy('empl_qual')
    ->distinct('empl_qual')
    ->all();


$empl_positions = array();
$empl_positions1[-1]['empl_pos'] = 'Все специальности';
$empl_positions2 = EmployeePosition::find()
    ->andWhere(['IN', 'employee_id', $advisor])
    ->andWhere(['is_doctor' => 1])
    ->orderBy('empl_pos')
    ->distinct('empl_pos')
    ->all();

$empl_positions = array_merge($empl_positions1, $empl_positions2);


$pluginOptions = ['allowClear' => true, 'placeholder' => 'Выберите...', 'width' => '100%'];

$form = ActiveForm::begin([
    'action' => ['index'],
    'id' => 'filter-form',
    'method' => 'get'
]) ?>

<?= $form->field($model, 'empl_city')->widget(Select2::className(), [
    'data' => ArrayHelper::map(Organization::find()->where(['is_hidden' => false, 'status' => 10])->orderBy('city')->all(), 'city', 'city'),
    'pluginOptions' => [
        'allowClear' => true,
        'placeholder' => 'Выберите город...'
    ]
])->error(false)->label('Город') ?>

    <div id="div_empl_org" style="display:none">
        <?= $form->field($model, 'empl_org')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Organization::find()->where(['is_hidden' => false, 'status' => 10])->orderBy('name')->all(), 'id', 'name'),
            'hideSearch' => true,
            'pluginOptions' => $pluginOptions,
        ])->error(false)->label('Организация') ?>
    </div>

    <div id="div_empl_dep" style="display:none">
        <?= $form->field($model, 'empl_dep')->widget(Select2::className(), [
            'data' => ArrayHelper::map(Department::find()->where(['status' => 10])->orderBy('name')->all(), 'id', function ($item) {
                return $item->name . ' (' . $item->address . ')';
            }),
            'hideSearch' => true,
            'pluginOptions' => $pluginOptions,
        ])->error(false)->label('Подразделение') ?>
    </div>

<?= $form->field($model, 'empl_name')
    ->textInput(['placeholder' => 'Поиск по ФИО специалиста...', 'value' => Yii::$app->request->get('empl_name')])
    ->error(false)->label('ФИО специалиста') ?>

<?= $form->field($model, 'empl_pos')
    ->radioList(ArrayHelper::map($empl_positions, 'empl_pos', 'empl_pos'), ['id' => 'search_empl_pos', 'class' => 'search-list scrollable'])
    ->error(false)->label('Специальность') . '<hr>' ?>

    <div class="row">
        <center><div class="col-md-12">
            <?= Html::submitButton('Найти', ['class' => 'btn btn-primary', 'style' => 'margin-right: 3px;']) ?>
        </div></center>
    </div>

<?php
$form->end();

$this->registerCss('
.checkbox label, .radio label {
    font-size: 13px;
    padding-left: 0px;
}
');
/*
 $("#search_empl_pos").on("ifChanged", function() {
    setTimeout(function() {
        $("#filter-form").submit();
    }, 500);
});
 */
$this->registerJs('
iCheckInit();


$(document).ready(function() {
    var city = $("#empl_city").val();
    var org = $("#empl_org").val();
    var dep = $("#empl_dep").val();
    
    if(city !="" ) {
      $("#div_empl_org").css("display","block");
    } else {
      $("#div_empl_org").css("display","none");
    }   
    
    if(org >0 ) {
      $("#div_empl_dep").css("display","block");
    } else {
      $("#div_empl_dep").css("display","none");
    }    
   

    if (city !== "" && org == "") { 
        populate("#empl_city", "#empl_org", "city");
    }
    if (city !== "" && org !== "" && dep == "") {
        populate("#empl_org", "#empl_dep", "org");
    }
});

$(document).on("change", "#empl_city", function() {
    city = $("#empl_city").val();
    if(city !="" ) {
      $("#div_empl_org").css("display","block");
    } else {
      $("#div_empl_org").css("display","none");
    }  
    
    populate("#empl_city", "#empl_org", "city");
    populate("#empl_city", "#empl_dep", "dep");
});

$(document).on("change", "#empl_org", function() {

        org = $("#empl_org").val();
        if(org >0 ) {
          $("#div_empl_dep").css("display","block");
        } else {
          $("#div_empl_dep").css("display","none");
        }     
    
    populate("#empl_org", "#empl_dep", "org");
});

function populate(from, target, type) {
    var value = $(from).val();
    
    $.ajax({
        url: "/data/populate-filter",
        method: "post",
        data: {type: type, value: value},
        success: function (result) {
            var depSelect = $(target);
            var options = {
                allowClear: true, 
                data: result.data,
                placeholder: "Выберите...",
                width: "100%"
            };            
            
            depSelect.empty();
            depSelect.select2(options);
        }
    });
}
');