<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\helpers\AppHelper;
use app\models\monitor\MonitorPassport;
use yii\bootstrap\Modal;

$this->title = 'Мониторинг: Протокол №' . $model->id;
$this->params['breadcrumbs'][] = ['label'=>'Мониторинг ', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$values = MonitorPassport::getValues();
?>

<style>
    #archive{
        padding-top: 100px;
    }
    .activityButton{
        margin: 10px;
    }

</style>

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
            'attribute'=>'comment',
            'label'=>'Другие жалобы',
            'format'=>'raw',
            'value'=>function ($model) {
                if (isset($model->employee->passport->protocols)) {
                    return $model->employee->passport->protocols->complain;
                } elseif (isset($model->patient->passport->protocols)) {
                    return $model->patient->passport->protocols->complain;
                }
            }
        ],
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
        [
            'attribute'=>'clinic',
            'format'=>'raw',
            'value'=>function ($model) {
                if ($model->employee) {
                    return (isset($model->employee->data) ? $model->employee->data->clinic : null);
                } elseif ($model->patient) {
                    return (isset($model->patient->data) ? $model->patient->data->clinic : null);
                } else {
                    return '-';
                }
            }
        ]
    ]
]) ?>


<?php  if ($model->employee) {
    $user = $model->employee->id;
} elseif ($model->patient) {
    $user = $model->patient->id;
} ?>

<a onclick="return showArchive()" ><i class="btn btn-default" style="border-radius: 8px; border: 1px solid #0f0f0f;">
        <i class="fa fa-book"></i> Перенести в архив </i></a>

<?= Html::a('Мониторинг', ['monitor/index'], ['class' => 'btn btn-primary', 'style'=>'border-radius: 8px;']) ?>


<hr>
<?= $this->render('_part/print', ['model'=>$model2, 'passport_id'=>$passport_id]) ?>
<?= $this->render('_part/table', ['model'=>$model2, 'passport_id'=>$passport_id]) ?>

<!-------------Окно в Архив--------------->
<script type="text/javascript">
    function showArchive(cart){
        $('#archive .modal-body').html(cart);
        $('#archive').modal();
    }
</script>
<?php

Modal::begin ([
    'id'=> 'archive',
    'footer' => null,
    'header' => null,
]); ?>

<form action="archive?user=<?= $user?>" method="post">
    <input type="hidden" name="<?= Yii::$app->request->csrfParam; ?>" value="<?= Yii::$app->request->csrfToken; ?>" />
    <center><p><b>Выберите причину переноса в архив</b></p></center>
   <center> <div class="row">
        <div class="form_radio_btn"><input id="radio-1" name="motive" type="radio" value="1" required>
            <label for="radio-1">Госпитализирован</label></div>
        <div class="form_radio_btn"><input id="radio-2" name="motive" type="radio" value="2">
            <label for="radio-2">Закончился срок</label></div>
        <div class="form_radio_btn"><input id="radio-3" name="motive" type="radio" value="3">
            <label for="radio-3">Ошибка в данных</label></div>
     <br><br>
    <label><input type="submit" class="btn btn-primary" style="border-radius: 6px;" value="Перенести в архив"></label>
    </div> </center>
</form>

<?php
Modal::end();
?>

<style>
    .modal-content {
        border-radius: 10px;
    }
    .modal-header {
        padding: 1px 15px 1px;
        background: #bcdcf8;
        border-radius: 10px;
    }
    .form_radio_btn {
        display: inline-block;
        margin-right: 10px;
    }
    .form_radio_btn input[type=radio] {
        display: none;
    }
    .form_radio_btn label {
        display: inline-block;
        cursor: pointer;
        padding: 0px 15px;
        line-height: 34px;
        border: 1px solid #999;
        border-radius: 6px;
        user-select: none;
    }

    /* Checked */
    .form_radio_btn input[type=radio]:checked + label {
        background: #077ce2;
    }

    /* Hover */
    .form_radio_btn label:hover {
        color: #fa894b;
        background: #d0f3ce;
    }

    /* Disabled */
    .form_radio_btn input[type=radio]:disabled + label {
        background: #efefef;
        color: #666;
    }
</style>
