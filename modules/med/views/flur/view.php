<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Запись №' . $model->f_id;
$this->params['breadcrumbs'][] = ['label'=>'Флюорография', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo ($model->f_norm_group !== 0) ? Html::tag('p', Html::a('Печать', ['print', 'id'=>$model->f_id], ['class'=>'btn btn-primary', 'target'=>'_blank'])) : null;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'f_fio_id',
            'value'=>function($model) {
                if ($model->patient) {
                    return implode(' ', [$model->patient->u_fam, $model->patient->u_ima, $model->patient->u_otc]);
                }
                return $model->f_fio_id;
            }
        ],
        [
            'attribute'=>'r_fio_id',
            'label'=>'Дата рождения',
            'value'=>function($model) {
                if ($model->patient) {
                    return date('d.m.Y', strtotime($model->patient->u_data_ros));
                }
                return null;
            }
        ],
        'f_n_medk',
        'f_organis',        
        'f_obl_issled',
        'f_o_group',
        'f_num_snimk',
        'f_vrach',
        [
            'attribute'=>'f_data',
            'value'=>function($model) {
                return date("H:i d.m.Y", strtotime($model->f_data));
            }
        ], 
        'f_diagnos',                
        'f_sakl_opis',
        'f_sakl',
        'f_sakl_vrach',
        [
            'attribute'=>'f_sakl_data',
            'value'=>function($model) {
                return ($model->f_sakl_data) ? date("H:i d.m.Y", strtotime($model->f_sakl_data)) : null;
            }
        ]        
    ]
]);