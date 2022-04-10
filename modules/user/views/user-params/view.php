<?php
use app\assets\ChartJsAsset;
use yii\helpers\Html;

switch ($param_name) {
    case 'cholesterol':
        $name = 'Холестерин';
        break;
    case 'height':
        $name = 'Рост';
        break;
    case 'pressure':
        $name = 'Артериальное давление';
        break;
    case 'pulse':
        $name = 'Пульс';
        break;
    case 'sleep':
        $name = 'Сон';
        break;
    case 'sugar':
        $name = 'Сахар в крови';
        break;
    case 'temperature':
        $name = 'Температура';
        break;
    case 'weight':
        $name = 'Вес';
        break;
}

$this->title = 'Детальная информация';
$this->params['breadcrumbs'][] = ['label'=>'Физические данные', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

ChartJsAsset::register($this);
?>

<div class="row">
    <div class="col-md-12" id="printable">
        <div class="row" style="margin-bottom: 20px;">
            <div class="col-md-6" id="details-stat">
                <?= Html::tag('p', 'Пациент: ' . Yii::$app->user->identity->fullname) ?>
                <?= Html::tag('p', 'Детальная информация: ' . $name) ?>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <?= Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-primary btn-print'])) ?>  
            </div>
        </div>
        <?php if ($conditions !== null) { ?>
            <div class="params-conditions" style="margin-bottom: 20px; text-align: center;">
                <?= Html::radioList(null, null, $conditions, [
                    'item'=>function ($index, $label, $name, $checked, $value) {
                        $class = ($value == 0) ? 'btn-default btn-info' : 'btn-default';
                        return Html::beginTag('label', ['class'=>'condition btn ' . $class, 'style'=>'color:' . (($value == 0) ? 'white' : '#444')]) .
                            Html::radio($name, $checked, ['value'=>$value, 'hidden'=>true]) . $label .
                            Html::endTag('label');
                    }
                ]) ?>
            </div>
        <?php } ?>
        <div class="params-stat">
            <?php if ($stat) { ?>                                                 
                <div class="chart">
                    <canvas id="chart" style="height: 350px;"></canvas>
                </div>
                <?= Html::endTag('div') ?>
            <?php } ?>
        </div>
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
$this->registerJs("
    var chartOptions = {
        legend: {
            display: false
        },
        scales: {
            xAxes: [{
                ticks: {
                    fontSize: 10,
                    fontStyle: 'italic'
                }
            }]
        }
    };
    var stat = " . json_encode($stat) . ";

    $('.btn-print').on('click', function() {    
        var imgData = document.getElementById('chart').toDataURL();        
        var content = '<!DOCTYPE html>';

        content += '<html>'
        content += '<head><title>Print canvas</title></head>';
        content += '<body>'
        content += document.getElementById('details-stat').innerHTML;
        content += '<br><img src=\"' + imgData + '\">';
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

    var ctx = document.getElementById('chart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: stat,
        options: chartOptions
    });

    $('.condition').on('click', function(e) {    
        var el = $(this);
        var param_name = '" . $param_name . "';
        var condition = el.children().val();

        $.ajax({
            url: '/user/user-params/get-stat-by-condition',
            method: 'post',
            data: {param_name: param_name, condition: condition, is_detail: true},
            success: function (result) {
                var ctx = document.getElementById('chart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: result,
                    options: chartOptions
                });

                $('.condition').removeClass('btn-info').css('color', '#444');
                el.addClass('btn-info').css('color', 'white');
            }
        });

        e.preventDefault();
    });
"); ?>