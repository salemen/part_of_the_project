<?php
use app\assets\ChartJsAsset;


ChartJsAsset::register($this);

$this->title = $title;
$this->params['breadcrumbs'][] = 'Статистика';
?>

<?= $this->render('/_chart/doughnut-picker', [
    'data'=>$data,
    'showOrg'=>false,
    'org'=>null,
    'showPeriod'=>true,
    'period'=>$period
]) ?>

<?php
$this->registerJs("
var doughnutStat = document.getElementById('doughnutStat').getContext('2d');

new Chart(doughnutStat, {
    type: 'doughnut',
    data: " . json_encode($data) . ",
    options: {
        legend: {
            position: 'left'
        },
        responsive: true,
        tooltips: {
            callbacks: {
                title: function(tooltipItem, data) {
                    return 'Кол-во консультаций';
                },
                label: function(tooltipItem, data) {                
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var label = dataset.label[tooltipItem.index];
                    var value = dataset.data[tooltipItem.index];

                    return ' ' + label + ': ' + value;
                }
            }
        }
    }
});
");