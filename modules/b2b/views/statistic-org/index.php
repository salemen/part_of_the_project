<?php
use app\assets\ChartJsAsset;


ChartJsAsset::register($this);

$this->title = $title;
$this->params['breadcrumbs'][] = 'Статистика';
?>

<?= $this->render('/_chart/doughnut-picker', [
    'data'=>$data,
    'showOrg'=>true,
    'org'=>$org,
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
        onClick: clickEvent,
        responsive: true,
        tooltips: {
            callbacks: {
                label: function(tooltipItem, data) {
                    var dataset = data.datasets[tooltipItem.datasetIndex];
                    var label = dataset.label[tooltipItem.index];
                    var total = dataset.data.reduce(function(previousValue, currentValue, currentIndex, array) {
                        return previousValue + currentValue;
                    });
                    var currentValue = dataset.data[tooltipItem.index];
                    var percentage = Math.floor(((currentValue/total) * 100) + 0.5);  

                    return ' ' + label + ': ' + currentValue + ' (' + percentage + '%)';
                }
            }
        }
    }
});

function clickEvent(event, array) {
    if (array.length !== 0) {        
        $.ajax({
            data: {
                org_name: array[0]._model.label,
                period: '" . $period . "'
            },
            method: 'post',
            url: '/b2b/statistic-org/view',
            success: function (result) {
                $('#modal-form').modal();
                $('.modal-body').html(result);
            }
        });
    }
}
");