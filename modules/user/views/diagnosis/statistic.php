<?php
use app\assets\ChartJsPluginAsset;

ChartJsPluginAsset::register($this);

$this->title = 'Мои диагнозы: Статистика';
$this->params['breadcrumbs'][] = ['label'=>'Мои диагнозы', 'url'=>['/user/diagnosis']];
$this->params['breadcrumbs'][] = $this->title;

$barChartHeight = $model['monthCount'] * 80 . 'px';
?>

<div class="row">    
    <div class="col-md-12">
        <div class="chart">
            <canvas id="barChart" style="height: <?= $barChartHeight ?>"></canvas>
        </div>
    </div>
</div>

<?php
$this->registerJs("
    var ctxBar = document.getElementById('barChart').getContext('2d');

    new Chart(ctxBar, {
        type: 'horizontalBar',
        data: " . json_encode($model) . ",
        options: {
            legend: {
                display: false
            },
            plugins: {
                datalabels: {
                    color: '#fff',
                    formatter: function(value, context) {
                        return (value == 0) ? '' : context.dataset.labelShort;
                    }
                }
            },
            responsive: true,
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
");