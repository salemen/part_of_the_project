<?php
use app\assets\ChartJsPluginAsset;
use yii\helpers\Html;
use app\models\research\ResearchType;

$this->title = 'Детальная информация';
$this->params['breadcrumbs'][] = ['label'=>'Результаты анализов', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

ChartJsPluginAsset::register($this);
?>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6" id="details-stat">
        <?= Html::tag('p',  Html::tag('b', 'Пациент: ') . $user->fullname) ?>
        <?= Html::tag('p', Html::tag('b', 'Пол: ') . ($user->sex ? 'Мужской' : 'Женский'), ['id'=>'user_sex', 'data'=>['value'=>$user->sex ? 'man' : 'woman']]) ?>
        <?= $user->sex ? null : Html::tag('p', Html::tag('b', 'Беременность: ') . Html::checkbox('is_pregnant', false, ['id'=>'user_is_pregnant'])) ?>
        <?= Html::tag('p', Html::tag('b', 'Вид исследования: ') . ResearchType::findOne($type_id)->name) ?>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <?= Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-primary btn-print'])) ?>  
    </div>
</div>
<?php if ($model !== null) { ?>
    <div class="params-conditions" style="margin-bottom: 20px; text-align: center;">
        <?= Html::radioList(null, null, $model, [
            'item'=>function($index, $label, $name, $checked, $value) use ($type_id) {                            
                return Html::tag('label', $label, ['class'=>'btn btn-xs btn-default condition', 'data'=>['index_id'=>$value, 'type_id'=>$type_id]]);
            }
        ]) ?>                                
    </div>                        
<?php } ?>
<div class="params-stat">                        
    <div class="chart">
        <canvas id="canvasChart" style="height: 400px; width: 100%;"></canvas>
    </div>
</div>

<?php
$this->registerCss('
@media print {
    body {
        font-family: "Times New Roman";
    }
}
');
$this->registerJs('
iCheckInit();

$("#user_is_pregnant").on({
    ifChanged: function() {
        var el = $(".condition.btn-info");
        loadChart(el);
    }
});

var ctx = document.getElementById("canvasChart").getContext("2d"); 
var chart = new Chart(ctx, {
    type: "line",
    data: {},
    options: {
        annotation: {
            annotations: []
        },
        plugins: {
            datalabels: {
                display: false
            }
        },
        responsive: false,
        scales: {
            xAxes: [{
                ticks: {
                    fontSize: 10,
                    fontStyle: "italic"
                }
            }],
            yAxes: [{
                offset: true
            }]
        }
    }        
});

$(document).ready(function() {
    var el = $(".condition").first();
    loadChart(el);
});

$(document).on("click", ".condition", function(e) {
    loadChart($(this));
    e.preventDefault();
});

$(".btn-print").on("click", function() {
    var imgData = document.getElementById("canvasChart").toDataURL();        
    var content = "<!DOCTYPE html>";

    content += "<html>";
    content += "<head><title>Print canvas</title></head>";
    content += "<body>";
    content += document.getElementById("details-stat").innerHTML;
    content += "<br><img src=\'" + imgData + "\' style=\'width: 100%;\'>";
    content += "</body>";
    content += "</html>";

    var printWin = window.open();

    printWin.document.open();
    printWin.document.write(content);
    printWin.document.close();

    printWin.document.addEventListener("load", function() {
        printWin.focus();
        printWin.print();
        printWin.close();            
    }, true);
});

function loadChart(el) {
    var index_id = el.data("index_id");
    var type_id = el.data("type_id");
    var user_sex = $("#user_sex").data("value");
    var isPregnant = $("#user_is_pregnant").prop("checked");
    
    if (isPregnant) {
        user_sex = "pregnant";
    }

    var data = {index_id, type_id, user_sex};

    $.ajax({
        async: false,
        data: data,
        method: "post",
        url: "/user/analysis/load-chart",
        success: function (result) {
            var norms = (result.norms) ? result.norms : [];
            var offset = (result.count == 1) ? true : false;

            chart.config.options.annotation.annotations = norms;
            chart.config.options.scales.xAxes[0].offset = offset;            
            chart.config.data = result.data;
            chart.update();

            $(".condition").removeClass("btn-info").css("color", "#444");
            el.addClass("btn-info").css("color", "white");
        }
    });
}
'); ?>