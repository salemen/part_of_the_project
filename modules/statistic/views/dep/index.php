<?php
use yii\helpers\Html;
use app\assets\ChartJsAsset;

ChartJsAsset::register($this);

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = ['label'=>'Статистика', 'url'=>['/statistic']];
$this->params['breadcrumbs'][] = $title;
?>

<div class="box box-body box-primary">
    <div class="row">
        <div class="col-md-12">
            <?= Html::tag('h4', $title, ['style'=>'text-align: center;']) ?>
            <?= $this->render('/_chart/doughnut-picker', [
                'data'=>$data,
                'showOrg'=>true,
                'org'=>$org,
                'showPeriod'=>true,
                'period'=>$period
            ]) ?>
        </div>   
        <div class="col-md-12" style="margin-top: 30px; text-align: center;">
            <?= Html::a('Вернуться назад', ['/statistic'], ['class'=>'btn btn-danger']) ?>
        </div>
    </div>
</div>

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
                dep_name: array[0]._model.label,
                period: '" . $period . "'
            },
            method: 'post',
            url: '/statistic/dep/view',
            success: function (result) {
                $('#modal-form').modal();
                $('.modal-body').html(result);
            }
        });
    }
}
");