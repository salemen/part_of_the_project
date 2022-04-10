<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\monitor\MonitorProtocolOrvi;

function getValue($model, $attribute) {
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
                    break;
                case 'p_bolmysh':
                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    break;
                case 'p_bolgorlo':
                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    break;
                case 'p_toshn':
                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    break;
                case 'p_bolgolova':
                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    break;
                case 'p_slab':
                    $class = ($data == 'выраженное') ? 'text-danger' : null;
                    break;
                case 'created_at':
                    $data = date('d.m.Y H:i', $data) . ' (МСК)';
                    break;
                case 'status':
                    switch ($data) {
                        case 10:
                            $data = '<span class="btn btn-xs btn-success" style="cursor: default;">Зеленый</span>';
                            break;
                        case 20:
                            $data = '<span class="btn btn-xs btn-warning" style="cursor: default;">Желтый</span>';
                            break;
                        case 30:
                            $data = '<span class="btn btn-xs btn-danger" style="cursor: default;">Красный</span>';
                            break;
                    }
            }

            echo  Html::tag('td', Html::tag('span', $data, ['class'=>$class]));
        }
    }
}

if ($model) {
    $instance = MonitorProtocolOrvi::instance();
    $attributes = $instance->getAttributes([
        'status',
        'result',
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



    echo Html::beginTag('div', ['id'=>'print-area', 'class'=>'table-responsive']);
    echo Html::beginTag('table', ['class'=>'table table-bordered table-striped detail-view']);
    echo Html::beginTag('tbody');
    foreach ($attributes as $key=>$attribute) {
        echo Html::beginTag('tr');
        echo Html::tag('th', $instance->getAttributeLabel($key));
        echo getValue($model, $key);
        echo Html::endTag('tr');
    }
    echo Html::endTag('tbody');
    echo Html::endTag('table');
    echo Html::endTag('div');
}
