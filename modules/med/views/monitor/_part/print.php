<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\monitor\MonitorProtocolOrvi;


function getValues($model, $attribute) {
    if ($model) {

        foreach ($model as $value) {
            $class = null;
            $data = $value->{$attribute};

            if ($data === '' || $data === null) {
                $data = '-';
            }

            switch ($attribute) {
                case 'p_temp':

                    $class = ((double)$data >= 37.6) ? 'text-danger' : null;

                    break;
                case 'p_kash':

                    $class = ((int)$data >= 15) ? 'text-danger' : null;
                    break;
                case 'p_kash_type':

                    $class = ((int)$value->p_kash >= 15 && $data == 'сухой') ? 'text-danger' : null;
                    if($data == 'влажный'){
                        $data = 'влаж.';
                    }elseif($data == 'сухой'){
                        $data = 'сух.';
                    }

                    break;
                case 'p_chast':

                    $class = ((int)$data >= 30) ? 'text-danger' : null;

                    break;
                case 'p_pulsmetr':

                    $class = ((int)$data && (int)$data < 95) ? 'text-danger' : null;

                    break;
                case 'p_diarea':

                    $class = ((int)$data >= 6) ? 'text-danger' : null;

                    break;
                case 'p_tyazh':

                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    if($data == 'выраженное'){
                        $data = 'выраж.';
                    }elseif($data == 'отсутствует'){
                        $data = 'нет';
                    }

                    break;
                case 'p_feel':

                    if($data == 'удовлетворительное'){
                        $data = 'удв.';
                    }elseif($data == 'хорошее'){
                        $data = 'хор.';
                    }

                    break;
                case 'p_bolmysh':

                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    if($data == 'выраженное'){
                        $data = 'вырож.';
                    }elseif($data == 'отсутствует'){
                        $data = 'нет.';
                    }

                    break;
                case 'p_bolgorlo':

                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    if($data == 'выраженное'){
                        $data = 'вырож.';
                    }elseif($data == 'отсутствует'){
                        $data = 'нет.';
                    }

                    break;
                case 'p_toshn':

                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    if($data == 'выраженное'){
                        $data = 'вырож.';
                    }elseif($data == 'отсутствует'){
                        $data = 'нет.';
                    }

                    break;
                case 'p_bolgolova':

                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    if($data == 'выраженное'){
                        $data = 'вырож.';
                    }elseif($data == 'отсутствует'){
                        $data = 'нет.';
                    }

                    break;
                case 'p_slab':

                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    if($data == 'выраженное'){
                        $data = 'вырож.';
                    }elseif($data == 'отсутствует'){
                        $data = 'нет.';
                    }

                    break;
                case 'created_at':

                    $data = date('d.m.y H:i', $data);

                    break;
                case 'status':
                    switch ($data) {
                        case 10:
                            echo Html::beginTag('td');
                            $data = '<span class="btn btn-xs btn-success" style="cursor: default;">Зеленый</span>';

                            break;
                        case 20:
                            echo Html::beginTag('td');
                            $data = '<span class="btn btn-xs btn-warning" style="cursor: default;">Желтый</span>';

                            break;
                        case 30:
                            echo Html::beginTag('td');
                            $data = '<span class="btn btn-xs btn-danger" style="cursor: default;">Красный</span>';

                            break;
                    }
            }

            echo Html::tag('th', Html::tag('span', $data, ['class'=>$class]),['style'=>'max-width:65px;']);
        }
    }
}

if ($model) {
    $instance = MonitorProtocolOrvi::instance();
    $attributes = $instance->getAttributes([
        // 'status',
        //'result',
        'created_at',
        'p_temp',
        'p_pulsmetr',
        'p_kash',
        'p_kash_type',
        'p_chast',
        'p_tyazh',
        'p_bolmysh',
        'p_bolgorlo',
        'p_diarea',
        'p_toshn',
        'p_bolgolova',
        'p_slab',
        'p_limf',
        'p_zapah',
        'p_lek_zhar',
        'p_lek_vir',
        'p_lek_antib',
        'p_feel',
        'complain'
    ]);


    echo Html::beginTag('div', ['class'=>'row', 'style'=>'margin-bottom: 10px;']);
    echo Html::beginTag('div', ['class'=>'col-md-10']);
    echo Select2::widget([
        'data'=>ArrayHelper::map(
            MonitorProtocolOrvi::find()->where(['passport_id'=>$passport_id])->orderBy(['created_at'=>SORT_DESC])->all(),
            function ($item) {
                return strtotime(date('d.m.Y', $item->created_at));
            },
            function ($item) {
                return date('d.m.Y', $item->created_at);
            }
        ),
        'name'=>'monitor_date',
        'options'=>['placeholder'=>'Выберите дату'],
        'pluginOptions'=>[
            'allowClear'=>true
        ],
        'pluginEvents'=>[
            'change'=>'function(e) {
                        var value = $(this).val();
                        insertParam("created_at", value);
                    }'
        ],
        'value'=>Yii::$app->request->get('created_at')
    ]);
    echo Html::endTag('div');
    echo Html::beginTag('div', ['class'=>'col-md-2']);
    echo Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-primary btn-block btn-print']));
    echo Html::endTag('div');
    echo Html::endTag('div');

    echo Html::beginTag('div', ['id'=>'print-area', 'class'=>'table-responsive', 'style'=>'display:none;']);
    echo Html::beginTag('table', ['style'=>'border:1px solid;', 'class'=>'table table-bordered table-striped detail-view']);

    foreach ($attributes as $key=>$attribute) {


        echo Html::beginTag('tr');
        echo Html::beginTag('td',['style'=>'width:80px;']);
        echo $instance->getAttributeLabel($key);
        echo getValues($model, $key);
        echo Html::endTag('td');
        echo Html::endTag('tr');

    }

    echo Html::endTag('table');
    echo Html::endTag('div');
}


$this->registerJs("
function insertParam(key, value) {
    key = encodeURI(key); 
    value = encodeURI(value);

    var kvp = document.location.search.substr(1).split('&');
    var i = kvp.length; 
    var x; 
    
    while (i--) {
        x = kvp[i].split('=');

        if (x[0] == key) {
            x[1] = value;
            kvp[i] = x.join('=');
            break;
        }
    }

    if (i<0) {
        kvp[kvp.length] = [key,value].join('=');
    }

    document.location.search = kvp.join('&'); 
}

$(document).on('click', '.btn-print', function() {
    var content = '<!DOCTYPE html>';
    
    content += '<html>'
    content += '<head><title>Печать документа</title></head>';
    content += '<body>'
    content += document.getElementById('print-area').innerHTML;
    content += '</body>';
    content += '</html>';
        
    var printWin = window.open();
    
    printWin.document.open();
    printWin.document.write(content);
    printWin.document.close();
    
    printWin.focus();
    printWin.print();
    printWin.close();
});
");