<?php
use kartik\daterange\DateRangePicker;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\data\Organization;
use app\models\employee\EmployeePosition;

$orgValue = null;
if ($org) {
    $orgValue = $org;    
}
$periodValue = null;
if ($period) {
    list($start_date, $end_date) = explode('-', $period);
    $periodValue = date('d.m.Y', $start_date) . '-' . date('d.m.Y', $end_date);
}

$k = 0;
if ($showOrg) { $k++; }
if ($showPeriod) { $k++; }
$orgIds = EmployeePosition::getOrgIds();
$orgArray = Organization::find()->where(['is_hidden'=>0, 'status'=>10])->andWhere(['IN', 'id', $orgIds])->orderBy('name')->all();
?>

<div class="row">    
    <?php if ($showOrg) { ?>
        <div class="col-md-<?= 12/$k ?>"> 
            <?= Html::tag('label', 'Организации', ['class'=>'control-label']) ?>
            <?= Select2::widget([
                'id'=>'org_select',
                'name'=>'select2_org',
                'value'=>$orgValue,
                'data'=>[''=>'Все организации'] + ArrayHelper::map($orgArray, 'id', 'name'),
                'pluginEvents'=>[
                    'change'=>'function(e) {
                        var value = $(this).val();
                        insertParam("org", value);
                    }'
                ]
            ]) ?>
        </div>    
    <?php } ?> 
    <?php if ($showPeriod) { ?>
        <div class="col-md-<?= 12/$k ?>"> 
            <?= Html::tag('label', 'Период', ['class'=>'control-label']) ?>
            <?= DateRangePicker::widget([
                'id'=>'period_picker',
                'name'=>'picker_period',
                'value'=>$periodValue,
                'options'=>['class'=>'form-control', 'placeholder'=>'Период'],
                'pluginEvents'=>[
                    'apply.daterangepicker'=>'function(e, p) {
                        var start = p.startDate.format("X");
                        var end = p.endDate.format("X");                    
                        var value = start + "-" + end;

                        insertParam("period", value);
                    }',
                    'cancel.daterangepicker'=>'function(e, p) {
                        insertParam("period", "");
                    }'
                ],
                'pluginOptions'=>[
                    'locale'=>[
                        'cancelLabel'=>'Очистить'
                    ]
                ]
            ]) ?> 
        </div>
    <?php } ?> 
    <div class="col-md-10 col-md-offset-1" style="margin-top: 30px; text-align: center;">
        <?= ($data) ? Html::tag('div', Html::tag('canvas', null, ['id'=>'doughnutStat']), ['class'=>'chart']) : 'Данных не найдено. Измените условия поиска или попробуйте позже.' ?>
    </div>
</div>  

<?php
$this->registerJs('
function insertParam(key, value) {
    key = encodeURI(key); 
    value = encodeURI(value);

    var kvp = document.location.search.substr(1).split("&");
    var i = kvp.length; 
    var x; 
    
    while (i--) {
        x = kvp[i].split("=");

        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join("=");
            break;
        }
    }

    if (i<0) {
        kvp[kvp.length] = [key,value].join("=");
    }

    document.location.search = kvp.join("&"); 
}
');