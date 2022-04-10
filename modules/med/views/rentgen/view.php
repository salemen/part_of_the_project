<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Запись №' . $model->r_id;
$this->params['breadcrumbs'][] = ['label'=>'Рентгенография', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo ($model->r_norm_group !== 0) ? Html::tag('p', Html::a('Печать', ['print', 'id'=>$model->r_id], ['class'=>'btn btn-primary', 'target'=>'_blank'])) : null;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'r_fio_id',
            'value'=>function($model) {
                if ($model->patient) {
                    return implode(' ', [$model->patient->u_fam, $model->patient->u_ima, $model->patient->u_otc]);
                }
                return $model->r_fio_id;
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
        'r_n_medk',
        'r_organis',
        'r_o_group',
        'r_num_snimk',        
        [
            'attribute'=>'r_obl_issled',
            'value'=>function($model) {
                return $model->r_obl_issled;
            }
        ],
        'r_vrach',
        [
            'attribute'=>'r_data',
            'value'=>function($model) {
                return date("H:i d.m.Y", strtotime($model->r_data));
            }
        ],                
        'r_sakl_opis',
        'r_sakl',
        'r_sakl_vrach',
        [
            'attribute'=>'r_sakl_data',
            'value'=>function($model) {
                return ($model->r_sakl_data) ? date("H:i d.m.Y", strtotime($model->r_sakl_data)) : null;
            }
        ] 
    ]
]);