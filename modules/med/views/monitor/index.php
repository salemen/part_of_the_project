<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\bootstrap\Alert;
use yii\helpers\Html;
use app\helpers\AppHelper;
use app\models\monitor\MonitorProtocolOrvi;
use app\models\user\UserData;
use app\models\data\Department;

$this->title = 'Мониторинг';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    .scroll{
        overflow-y: scroll;
    }

</style>

<?= Alert::widget([
    'body'=>$this->render('_part/stat'),
    'closeButton'=>false,
    'options'=>[
        'class'=>'alert-default'
    ]
]) ?>

<div class="scroll">
    <?= GridView::widget([
        'dataProvider'=>$dataProvider,
        'filterModel'=>$searchModel,
        'panel'=>[
            'before'=>$this->render('_search', ['model'=>$searchModel]),
            'heading'=>false
        ],
        'responsive'=>false,
        'columns'=>[
            [
                'attribute'=>'period_start',
                'contentOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 60px;'],
                'header'=>'ДН',
                'value'=>function ($model) {
                    $start = strtotime($model->period_start);
                    $now = date('U');
                    $dayCount = round(($now - $start) / 86400);
                    $dayCount = ($dayCount == 0) ? 1 : $dayCount;
                    return $dayCount;
                }
            ],
            [
                'attribute'=>'user_id',
                'contentOptions'=>['class'=>'kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle','style'=>'width: 120px !important;'],
                'header'=>'ФИО',
                'value'=>function ($model) {
                    if ($model->employee) {
                        $dr = $model->employee->user_birth;
                        return $model->employee->fullname . ' (' . AppHelper::calculateAge($dr, true) . ')';
                    } elseif ($model->patient) {
                        $dr = $model->patient->user_birth;
                        return $model->patient->fullname . ' ( ' . AppHelper::calculateAge($dr, true) . ')';
                    } else {
                        return '-';
                    }
                }
            ],
            [
                'attribute'=>'user_id',
                'header'=>'Город',
                'contentOptions'=>['class'=>'kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'value'=>function ($model) {
                    if ($model->employee) {
                        return $model->employee->city;
                    } elseif ($model->patient) {
                        return $model->patient->city;
                    } else {
                        return '-';
                    }
                }
            ],
            [
                'attribute'=>'user_id',
                'contentOptions'=>['class'=>'kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'header'=>'Номер телефона',
                'value'=>function ($model) {
                    if ($model->employee) {
                        return ($model->employee->phone ? : '-') . ' / ' . ($model->employee->phone_work ? : '-');
                    } elseif ($model->patient) {
                        return $model->patient->phone;
                    } else {
                        return '-';
                    }
                }
            ],
            [
                'attribute'=>'',
                'header'=> 'Тип',
                'contentOptions'=>['class'=>'kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'value'=>function () {
                    return 'ОРВИ / COVID-19';
                }
            ],
            [
                'attribute'=>'clinic',
                'header'=> 'Поликлиника',
                'contentOptions'=>['class'=>'kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width:350px;'],
                'value'=>function ($model) {
                    if ($model->data->clinic) {
                        return $model->data->clinic;
                    } else {
                        return "-";
                    }
                },
                'filterType'=>GridView::FILTER_SELECT2,
                'filterWidgetOptions'=>[
                    'data'=>ArrayHelper::map(Department::find()->distinct()->all(), 'name', 'name'),
                    'hideSearch'=>false,
                    'options'=>['placeholder'=>'Фильтр'],
                    'pluginOptions'=>['allowClear'=>true]
                ],
            ],
            [
                'attribute'=>'protocol_status',
                'contentOptions'=>['class'=>'kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'header'=>'Рекомендации',
                'value'=>function ($model) {
                    if ($model::isNotActive($model->id)) {
                        return 'Нет новых отметок в протоколе';
                    }
                    switch ($model->protocol_status) {
                        case $model::STATUS_DEFAULT:
                        case $model::STATUS_SUCCESS:
                            return '-';
                        case $model::STATUS_WARNING:
                            return 'Необходимо связаться по телефону';
                        case $model::STATUS_DANGER:
                            return 'Необходим срочный вызов врача';
                    }
                }
            ],
            [
                'attribute'=>'passport_status',
                'format'=>'raw',
                'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 30px;'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 30px !important;'],
                'header'=>'Эпид. статус',
                'value'=>function ($model) {
                    switch ($model->passport_status) {
                        case $model::STATUS_DEFAULT:
                            return '<span class="btn btn-xs btn-default" style="cursor: default;">+</span>';
                        case $model::STATUS_SUCCESS:
                            return '';
                        case $model::STATUS_DANGER:
                            return '<span class="btn btn-xs btn-danger" style="cursor: default;">+</span>';
                    }
                }
            ],
            [
                'attribute'=>'protocol_status',
                'format'=>'raw',
                'contentOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 50px;'],
                'value'=>function ($model) {
                    switch ($model->protocol_status) {
                        case $model::STATUS_DEFAULT:
                            return '<span class="btn btn-block btn-xs btn-default" style="cursor: default;">Не заполнен</span>';
                        case $model::STATUS_SUCCESS:
                            return '<span class="btn btn-block btn-xs btn-success" style="cursor: default;">Зеленый</span>';
                        case $model::STATUS_WARNING:
                            return '<span class="btn btn-block btn-xs btn-warning" style="cursor: default;">Желтый</span>';
                        case $model::STATUS_DANGER:
                            return '<span class="btn btn-block btn-xs btn-danger" style="cursor: default;">Красный</span>';
                    }
                }
            ],
            [
                'attribute'=>'updated_at',
                'contentOptions'=>['class'=>'kv-align-center kv-align-middle'],
                'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 50px;'],
                'format'=>'raw',
                'value'=>function ($model) {
                    $lastActivity = MonitorProtocolOrvi::find()->where(['passport_id'=>$model->id])->max('created_at');
                    if ($lastActivity === null) {
                        return date('d.m.Y H:i', $model->created_at);
                    }
                    return date('d.m.Y H:i', $lastActivity);
                }
            ],
            [
                'class'=>'kartik\grid\ActionColumn',
                'header'=>'Исполнено',
                'template'=>'{check}',
                'buttons'=>[
                    'check'=>function ($url, $model) {
                        if ($model->is_checked) {
                            return '<i class="fa fa-check text-success" title="Дата отметки ' . date('d.m.Y H:i', $model->checked_at) . '"></i>';
                        } else {
                            return Html::a('<i class="fa fa-remove text-danger"></i>', $url, ['title'=>'Изменить отметку об исполнении']);
                        }
                    }
                ]
            ],
            [
                'class'=>'kartik\grid\ActionColumn',
                'header'=>'Передано врачу',
                'template'=>'{to-doc}',
                'buttons'=>[
                    'to-doc'=>function ($url, $model) {
                        if ($model->is_to_doc) {
                            return '<i class="fa fa-check text-success"></i>';
                        } else {
                            return Html::a('<i class="fa fa-remove text-danger"></i>', $url, ['title'=>'Изменить отметку о передаче врачу']);
                        }
                    }
                ]
            ],
//        [
//            'attribute'=>'',
//            'format'=>'raw',
//            'contentOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 30px;'],
//            'headerOptions'=>['class'=>'kv-align-center kv-align-middle', 'style'=>'width: 30px !important;'],
//            'header'=>'Вызов врача',
//            'value'=>function ($model) {
//                    return '<button class="btn btn-block btn-xs btn-success">!</button>';
//
//            }
//        ],
            [
                'class'=>'kartik\grid\ActionColumn',
                'template'=>'{view} {update}'
            ]
        ]
    ]) ?>
</div>
