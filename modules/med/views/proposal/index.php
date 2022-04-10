<?php
use kartik\grid\GridView;
use app\models\user\UserProposal;

$this->title = 'Заявки';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'dataProvider'=>$dataProvider,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'status',
            'format'=>'raw',
            'value'=>function ($model) {
                if ($model->status == UserProposal::STATUS_ONHOLD) {
                    return '<span class="text-danger">Ожидает</span>';
                } elseif ($model->status == UserProposal::STATUS_ONWORK) {
                    return '<span class="text-warning">В работе</span>';
                } elseif ($model->status == UserProposal::STATUS_SUCCESS) {
                    return ($model->proposalBlank) ? '<span class="text-success">Обработана</span>' : '<span class="text-warning">В работе</span>';
                } elseif ($model->status == UserProposal::STATUS_DELETED) {
                    return '<span class="text-default">Удалена</span>';
                }
            }
        ],
        [
            'attribute'=>'type_id',
            'value'=>function ($model) {
                if ($model->type_id == UserProposal::TYPE_CALL_DOCTOR) {
                    return 'Вызов врача на дом';
                }
            }
        ],        
        [
            'attribute'=>'user_id',
            'value'=>function($model) {
                if ($model->employee) {
                    return $model->employee->fullname;
                } elseif ($model->patient) {
                    return $model->patient->fullname;
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y H:i', $model->created_at) . ' (МСК)';
            }
        ],
        [
            'attribute'=>'updated_by',
            'value'=>function ($model) {
                if ($model->updater) {
                    return $model->updater->fullname;
                } else {
                    return null;
                }
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{view} {delete}'
        ]
    ]
]);