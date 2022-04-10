<?php
use yii\helpers\Html;
use app\assets\ChartJsPluginAsset;

ChartJsPluginAsset::register($this);

$this->title = 'Статистика заболеваний';
$this->params['breadcrumbs'][] = ['label'=>'Мои диагнозы', 'url'=>['/user/diagnosis']];
$this->params['breadcrumbs'][] = $this->title;

$barChartHeight = $model['monthCount'] * 100 . 'px';
?>

<div class="row">    
    <div class="col-md-6" id="details-stat">
        <?= Html::tag('p', 'Пациент: ' . Yii::$app->user->identity->fullname) ?>
        <?= Html::tag('p', 'Дата рождения: ' . Yii::$app->user->identity->user_birth) ?>
    </div>
    <div class="col-md-6" style="text-align: right;">
        <?= Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-primary btn-print'])) ?>
    </div>
    <div class="col-md-12">
        <canvas id="barChart" style="height: <?= $barChartHeight ?>; width: 100%;"></canvas>
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
$this->registerJsFile('/js/chartjs.functions.js');
$this->registerJs("
var barChart = document.getElementById('barChart');
var ctxBar = barChart.getContext('2d');

new Chart(ctxBar, {
    type: 'horizontalBar',
    data: " . json_encode($model) . ",
    options: {
        legend: {
            align: 'start',
            display: true,
            fullWidth: false,
            labels: {
                usePointStyle: true
            },
            onClick: toggleLegend,
            position: 'bottom'
        },
        plugins: {
            datalabels: {
                color: '#fff',
                formatter: function(value, context) {
                    return (value == 0) ? '' : context.dataset.labelShort;
                }
            }
        },
        responsive: false,
        scales: {
            xAxes: [{ 
                afterDataLimits (scale) {
                    var sm = scale.max;
                    scale.max = (sm < 10) ? Math.round(sm * 100 / 80) : sm;
                },
                stacked: true,
                ticks: {
                    min: 0,
                    stepSize: 1
                }                    
            }],
            yAxes: [{
                stacked: true
            }]
        },
        title: {
            display: true,
            text: 'Статистика заболеваний (помесячно)'
        },
        tooltips: {
            mode: 'point'
        }
    }
});

$('.btn-print').on('click', function() {
    var content = '<!DOCTYPE html>';
    var imgData = barChart.toDataURL();

    content += '<html>';
    content += '<head><title>Print canvas</title></head>';
    content += '<body>';
    content += document.getElementById('details-stat').innerHTML;
    content += '<br><img src=\"' + imgData + '\" style=\"width: 100%;\">';
    content += '</body>';
    content += '</html>';

    var printWin = window.open();

    printWin.document.open();
    printWin.document.write(content);
    printWin.document.close();

    printWin.document.addEventListener('load', function() {
        printWin.focus();
        printWin.print();
        printWin.close();            
    }, true);
});
");