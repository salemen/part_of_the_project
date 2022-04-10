<?php
use yii\helpers\Html;
use app\models\monitor\MonitorProtocolOrvi;

function getValue($model, $attribute) {
    if ($model) {
        foreach ($model as $value) {
            $data = $value->{$attribute};
            if ($data === '' || $data === null) {
                $data = '-';
            }
            if ($attribute == 'created_at') {
                echo Html::tag('td', date('d.m.Y H:i', $data));
            } else {
                echo Html::tag('td', $data);
            }
        }
    }
}

if ($model) {
    $instance = MonitorProtocolOrvi::instance();
    $attributes = $instance->getAttributes([
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
        'p_feel'
    ]);

    echo Html::beginTag('div', ['class'=>'table-responsive']);
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