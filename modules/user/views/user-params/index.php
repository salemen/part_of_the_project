<?php
use app\assets\ChartJsAsset;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Физические данные';
$this->params['breadcrumbs'][] = $this->title;
$this->params['dashboard'][] = true;

ChartJsAsset::register($this);
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-red text-center">
            Для доступа к этой странице вы можете использовать быструю ссылку - 
            <?= Html::a('Получить ссылку', '#', ['class'=>'btn btn-xs btn-danger btn-go-link', 'data'=>['user_id'=>Yii::$app->user->id, 'url'=>Url::current()]]) ?>
        </div>
    </div>
    <?php foreach ($params_stats as $key=>$array) { ?>
        <div class="col-md-4 col-sm-4 col-xs-12">
            <div class="box box-body box-red" style="height: 440px;">
                <div class="row" style="border-bottom: 1px solid #ddd;">
                    <div class="col-md-12">
                        <div class="params-header">
                            <div class="row">
                                <div class="col-md-8 col-sm-8 col-xs-8">
                                    <div class="params_name">
                                        <?= Html::a('<i class="btn btn-lg fa fa-area-chart"></i>' . $array['name'], ['view', 'param_name'=>$key], ['title'=>'Подробнее']) ?>
                                    </div>
                                </div>
                                <div class="col-md-4 col-sm-4 col-xs-4">
                                    <div class="params_btn_add" style="float: right; margin-bottom: 5px;">
                                        <?= Html::a('Добавить', '#', ['class'=>'btn btn-primary','param_name'=>$key]) ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px; margin-bottom: 5px;">
                    <div class="col-md-12" style="text-align: center;">
                        <?php if (array_key_exists('cond', $array)) { $val = $array['cond']['checked_val']; ?>
                            <div class="params-conditions-<?= $key ?>">
                                <?= Html::radioList(null, null, $array['cond']['values'], [
                                    'item'=>function ($index, $label, $name, $checked, $value) use($key, $val) {
                                        $class = ($value == $val) ? 'btn-default btn-info' : 'btn-default';

                                        return Html::beginTag('label', ['class'=>'condition condition-' . $key . ' btn ' . $class . ' btn-xs', 'param_name'=>$key, 'style'=>'color:' . (($value == $val) ? 'white' : '#444')]) .
                                            Html::radio($name, $checked, ['value'=>$value, 'hidden'=>true]) . $label .
                                            Html::endTag('label');
                                    }
                                ]) ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="params-form-<?= $key ?>"></div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="params-stat-<?= $key ?>">
                            <?php if ($array['stat']) { ?>
                                <div class="chart">
                                    <canvas id="<?= $key ?>Chart" style="height: 300px;"></canvas>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php } ?>
</div>
<?php
$this->registerJs("
var array = " . json_encode($params_stats) . ";
var chartOptions = {
    legend: {
        display: false
    },
    onClick: clickEvent,
    scales: {
        xAxes: [{
            ticks: {
                fontSize: 10,
                fontStyle: 'italic'
            }
        }]
    }
};

for (var key in array) {
    var stat = array[key]['stat'];
    var ctx = document.getElementById(key + 'Chart').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: stat,
        options: chartOptions
    });
}

$('.params_btn_add a').on('click', function(e) {    
    var param_name = $(this).attr('param_name');
    
    $.ajax({
        data: {param_name: param_name},
        method: 'post',
        url: '/user/user-params/crud',
        success: function (result) {
            $('.params-stat-' + param_name).css('display', 'none');
            $('.params-conditions-' + param_name).css('display', 'none');
            $('.params-form-' + param_name).css('display', 'block');
            $('.params-form-' + param_name).html(result);
        }
    });
    
    e.preventDefault();
});

$('.condition').on('click', function(e) {
    e.preventDefault();
    var el = $(this);
    var param_name = el.attr('param_name');
    var condition = el.children().val();

    $.ajax({
        data: {param_name: param_name, condition: condition, is_detail: false},        
        method: 'post',
        url: '/user/user-params/get-stat-by-condition',
        success: function (result) {
            var ctx = document.getElementById(param_name + 'Chart').getContext('2d');
            var chart = new Chart(ctx, {
                type: 'line',
                data: result,
                options: chartOptions
            });
            
            $('.condition-' + param_name).removeClass('btn-info').css('color', '#444');
            el.addClass('btn-info').css('color', 'white');
        }
    });
});

// form actions
$(document).on('click', '.submit', function(e) {    
    var param_name = $(this).attr('param_name');
    var model_id = $(this).attr('model_id');
    
    var params = (typeof model_id === 'undefined') ? '&param_name=' + param_name : '&param_name=' + param_name + '&model_id=' + model_id;
    
    $.ajax({
        data: $('#form-' + param_name).serialize() + params,
        method: 'post',
        url: '/user/user-params/crud',
        success: function (result) {
            if (result.success) {
                $('.params-form-' + param_name).css('display','none');
                $('.params-form-' + param_name).html(null);
                
                var ctx = document.getElementById(param_name + 'Chart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: result.stat,
                    options: chartOptions
                });
                
                if (result.cond !== null) {
                    var radios = $('.params-conditions-' + param_name + ' input[type=\"radio\"]')
                    $('.condition-' + param_name).removeClass('btn-info').css('color', '#444');
                    
                    for(var item in radios) {
                        var el = $(radios[item]);
                        
                        if (el.val() == result.cond) {
                            el.parent().addClass('btn-info').css('color', 'white');
                            break;
                        }
                    }
                }
  
                $('.params-conditions-' + param_name).css('display', 'block');
                $('.params-stat-' + param_name).css('display', 'block');
            } else {
                $('#form-' + param_name).yiiActiveForm('updateMessages', result.validation, true);
            }   
        }
    });
    
    e.preventDefault();
});

$(document).on('click', '.cancel', function(e) {    
    var param_name = $(this).attr('param_name');
    
    $('.params-form-' + param_name).css('display', 'none');
    $('.params-form-' + param_name).html(null);
    $('.params-conditions-' + param_name).css('display', 'block');
    $('.params-stat-' + param_name).css('display', 'block');
    
    e.preventDefault();
});

$(document).on('click', '.btn-go-link', function(e) { 
    var data = $(this).data();
    
    $.alert({
        buttons: {
            ok: {
                btnClass: 'btn-primary',
                text: 'Закрыть'                
            }
        },
        content: function () {
            var self = this;
            
            return $.ajax({
                data: data,
                method: 'post',
                url: '/go/get-link',
            }).done(function (result) {
                self.setContent(result);
            });
        },
        theme: 'modern',
        title: false
    });
    e.preventDefault();
});

function clickEvent(event, array) {    
    if (array.length !== 0) {  
        var index = array[0]._index;
        var dataset = this.config.data.datasets[0];
        
        var param_name = dataset.param_name;
        var model_id = dataset.data_id[index];

        $.ajax({
            data: {param_name: param_name, model_id: model_id},            
            method: 'post',
            url: '/user/user-params/crud',
            success: function (result) {
                $('.params-stat-' + param_name).css('display', 'none');
                $('.params-conditions-' + param_name).css('display', 'none');
                $('.params-form-' + param_name).css('display', 'block');
                $('.params-form-' + param_name).html(result);
            }
        });
    }
}
");
?>