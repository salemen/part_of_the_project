<?php
use app\assets\ChartJsAsset;

ChartJsAsset::register($this);
?>

<div id="accordion" style="margin-top: 10px;">
    <div id="collapseOne" class="panel-collapse collapse">        
        <div class="row">
            <div class="col-md-12">
                <div class="chart">
                    <canvas id="my-canvas" style="height: 400px;"></canvas>
                </div>
            </div>
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