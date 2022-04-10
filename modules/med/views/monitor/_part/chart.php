<?php
use app\assets\ChartJsAsset;

ChartJsAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <div class="chart">
            <canvas id="my-canvas" style="height: 400px;"></canvas>
        </div>
    </div>
</div>
    
<?php
$this->registerJs("
var ctx = document.getElementById('my-canvas').getContext('2d');

new Chart(ctx, {
    type: 'line',
    data: " . $data . ",
    options: {
        responsive: true,
        scales: {
            yAxes: [{
                ticks: {
                    min: 0
                }
            }]
        },
        title: {
            display: true,
            text: 'Динамика изменения параметров'
        }
    }
});
");