<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AppHelper;
use app\models\user\UserProposal;

$this->title = 'Заявка №' . $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Заявки', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

echo DetailView::widget([
    'model'=>$model,
    'attributes'=>[
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
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y H:i', $model->created_at) . ' (МСК)';
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
            'attribute'=>'user_id',
            'label'=>'Дата рождения',
            'value'=>function($model) {
                if ($model->employee) {
                    $dr = $model->employee->user_birth;
                    return $dr . ' / ' . AppHelper::calculateAge($dr, true);
                } elseif ($model->patient) {
                    $dr = $model->patient->user_birth;
                    return $dr . ' / ' . AppHelper::calculateAge($dr, true);
                } else {
                    return null;
                }
            }
        ],        
        [
            'attribute'=>'user_id',
            'label'=>'Контактные данные',
            'value'=>function($model) {
                if ($model->employee) {
                    return ($model->employee->phone ? $model->employee->phone . ' / ' : null) . $model->employee->phone_work . ', ' . $model->employee->email;
                } elseif ($model->patient) {
                    return $model->patient->phone . ($model->patient->email ? ', ' . $model->patient->email : null);
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'user_id',
            'label'=>'Адрес',
            'value'=>function($model) {
                if ($model->employee) {
                    return $model->employee->city . ($model->employee->data ? ', ' . $model->employee->data->address : null);
                } elseif ($model->patient) {
                    return $model->patient->city . ($model->patient->data ? ', ' . $model->patient->data->address : null);
                } else {
                    return null;
                }
            }
        ],        
        [
            'attribute'=>'user_id',
            'label'=>'Полис ОМС',
            'value'=>function($model) {
                if ($model->employee && $model->employee->data) {
                    return '№' . $model->employee->data->polis_oms_number . ' ' . $model->employee->data->polis_oms_org;
                } elseif ($model->patient && $model->patient->data) {
                    return '№' . $model->patient->data->polis_oms_number . ' ' . $model->patient->data->polis_oms_org;
                } else {
                    return null;
                }
            }
        ],
        [
            'attribute'=>'comment',
            'label'=>'Жалобы'
        ], 
        [
            'attribute'=>'param1',
            'label'=>'Дата и время желаемого приезда врача'
        ],         
        [
            'attribute'=>'updated_by',
            'value'=>function ($model) {
                return ($model->updater) ? $model->updater->fullname : null;
            }
        ]
    ]
]);
                
if (($model->updated_by == Yii::$app->user->id) && ($model->status == UserProposal::STATUS_ONWORK || !$model->proposalBlank)) {    
    echo Html::a('Перейти к заполнению бланка заявки', ['/med/proposal-blank/create', 'proposal_id'=>$model->id], ['class'=>'btn btn-primary']);
}