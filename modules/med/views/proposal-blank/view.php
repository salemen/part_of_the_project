<?php
use yii\helpers\Html;
use yii\widgets\DetailView;

$this->title = 'Бланк №' . $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Бланки', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo Html::beginTag('p');
echo Html::a('Изменить', ['update', 'id'=>$model->id], ['class'=>'btn btn-primary', 'style'=>'margin-right: 3px;']);
echo Html::a('Распечатать бланк', ['print', 'id'=>$model->id], ['class'=>'btn btn-warning']);
echo Html::endTag('p');

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        'user_f',
        'user_i',
        'user_o',
        'user_birth',
        'who_calls',
        'phone',
        'address',
        'guide',
        'reason',
        'complaint:ntext',
        'visit_date',
        [
            'attribute'=>'payment',
            'value'=>function ($model) {
                return ($model->payment) ? 'Да' : 'Нет';
            }
        ],
        'cost',
        [
            'attribute'=>'',
            'label'=>'Номер полюса ОМС',
            'value'=>function ($model) {
                if (isset($model->proposal->patient->data)) {
                    return $model->proposal->patient->data->polis_oms_number;
                }elseif (isset($model->proposal->employee->data)) {
                    return $model->proposal->employee->data->polis_oms_number;
                }
            }
        ],
        [
            'attribute'=>'dep_id',
            'value'=>function ($model) {
                if ($model->department){
                    return $model->department->name;
                }elseif (isset($model->proposal->patient->data)) {
                    return $model->proposal->patient->data->clinic;
                }elseif (isset($model->proposal->employee->data)) {
                    return $model->proposal->employee->data->clinic;
                }
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y H:i', $model->created_at);
            }
        ],
        [
            'attribute'=>'created_by',
            'value'=>function ($model) {
                return ($model->creater) ? $model->creater->fullname : null;
            }
        ]
    ]
]);