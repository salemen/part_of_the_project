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
                'showOrg'=>false,
                'org'=>null,
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