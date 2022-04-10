<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AppHelper;
use app\models\monitor\MonitorPassport;

$this->title = 'Архив: Протокол №' . $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Архив ', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$values = MonitorPassport::getValues();
?>

<?= DetailView::widget([
    'model'=>$model,
    'attributes'=>[
        [
            'attribute'=>'passport_status',
            'format'=>'raw',
            'value'=>function ($model) {
                if(isset($model->protocols->covid)&&$model->protocols->covid == 1){
                    return '<span class="btn btn-xs btn-danger" style="cursor: default;">Подтвержденный COVID</span>';
                }else{
                    switch ($model->passport_status) {
                        case $model::STATUS_DEFAULT:
                            return '<span class="btn btn-xs btn-default" style="cursor: default;">Не заполнен</span>';
                        case $model::STATUS_SUCCESS:
                            return '<span class="btn btn-xs btn-success" style="cursor: default;">Зеленый</span>';
                        case $model::STATUS_WARNING:
                            return '<span class="btn btn-xs btn-warning" style="cursor: default;">Желтый</span>';
                        case $model::STATUS_DANGER:
                            return '<span class="btn btn-xs btn-danger" style="cursor: default;">Красный</span>';
                    }
                }
            }
        ],
        [
            'attribute'=>'protocol_type',
            'value'=>function () {
                return 'ОРВИ / COVID-19';
            }
        ],
        [
            'attribute'=>'period_start',
            'label'=>'Дата постановки / снятия',
            'value'=>function ($model) {
                $start = strtotime($model->period_start);
                $now = date('U');
                $dayCount = round(($now - $start) / 86400);
                return $model->period_start . ' - ' . $model->period_end . ' (на наблюдении ' . AppHelper::declension((($dayCount == 0) ? 1 : $dayCount), 'день', 'дня', 'дней') . ')';
            }
        ],
        [
            'attribute'=>'user_id',
            'label'=>'ФИО',
            'value'=>function ($model) {
                if ($model->employee) {
                    return $model->employee->fullname;
                } elseif ($model->patient) {
                    return $model->patient->fullname;
                } else {
                    return '-';
                }
            }
        ],
        [
            'attribute'=>'user_id',
            'format'=>'raw',
            'label'=>'Дата рождения / Возраст',
            'value'=>function ($model) {
                if ($model->employee) {
                    $dr = $model->employee->user_birth;
                } elseif ($model->patient) {
                    $dr = $model->patient->user_birth;
                } else {
                    return '-';
                }
                $age = AppHelper::calculateAge($dr);
                switch ($age) {
                    case 0:
                    case ($age < 45):
                        $class = 'text-success';
                        break;
                    case ($age >= 45 && $age < 65):
                        $class = 'text-warning';
                        break;
                    case ($age >= 65):
                        $class = 'text-danger';
                        break;
                }
                return  $dr . ' / ' . Html::tag('span', AppHelper::calculateAge($dr, true), ['class'=>$class]);
            }
        ],
        [
            'attribute'=>'user_id',
            'label'=>'Адрес',
            'value'=>function ($model) {
                if ($model->employee) {
                    return $model->employee->city . ' ' . (isset($model->employee->data) ? $model->employee->data->address : null);
                } elseif ($model->patient) {
                    return $model->patient->city . ' ' . (isset($model->patient->data) ? $model->patient->data->address : null);
                } else {
                    return '-';
                }
            }
        ],
//        [
//            'attribute'=>'reason',
//            'format'=>'raw',
//            'value'=>function ($model) use ($values) {
//                $reason = (int)$model->reason;
//                $class = ($reason !== 10) ? 'text-danger' : 'text-success';
//                return Html::tag('span', $values['reason'][$reason], ['class'=>$class]);
//            }
//        ],
        [
            'attribute'=>'sicks',
            'format'=>'raw',
            'value'=>function ($model) {
                if ($model->sicks) {
                    return Html::tag('span', $model->sicks, ['class'=>'text-danger']);
                }
                return '-';
            }
        ],
//        [
//            'attribute'=>'clinic',
//            'format'=>'raw',
//            'value'=>function ($model) {
//                if ($model->employee) {
//                    return (isset($model->employee->data) ? $model->employee->data->clinic : null);
//                } elseif ($model->patient) {
//                    return (isset($model->patient->data) ? $model->patient->data->clinic : null);
//                } else {
//                    return '-';
//                }
//            }
//        ],
        [
            'attribute'=>'motive',
            'format'=>'raw',
            'value'=>function ($model) {
                if ($model->motive) {
                    return Html::tag('span', $model->motive, ['class'=>'text-danger']);
                }
                return '-';
            }
        ],
    ]
]) ?>

<?php  if ($model->employee) {
    $user = $model->employee->id;
} elseif ($model->patient) {
    $user = $model->patient->id;
} ?>
<?= Html::a('Восстановить с архива', ['archive-up' , 'user' => $user], [
    'class' => 'btn btn-danger',
    'data' => [
        'confirm' => 'Вы действительно хотите восстановить с архива данные мониторинга?',
        'method' => 'post',
    ],
]) ?>
    <hr>
<?= $this->render('_part/print', ['model'=>$model2, 'passport_id'=>$passport_id]) ?>
<?= $this->render('_part/table', ['model'=>$model2, 'passport_id'=>$passport_id]) ?>