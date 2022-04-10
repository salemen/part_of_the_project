<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Мои расшифровки ЭКГ';
$this->params['breadcrumbs'][] = $this->title;

echo GridView::widget([
    'id'=>'patient-cardio',
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Новая заявка', ['/cardio'], ['class'=>'btn btn-primary'])
    ],
    'pjax'=>true,
    'responsive'=>false,
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],                            
        [
            'attribute'=>'employee_id',
            'label'=>'Исполнитель',
            'value'=>function ($model) {
                if ($model->employee_id == null) {
                    return 'Исполнитель пока не назначен';
                } else {
                    return $model->employee->fullname;
                } 
            }
        ],
        [
            'attribute'=>'is_end',
            'format'=>'raw',
            'label'=>'Статус заявки',
            'value'=>function ($model) {
                if ($model->is_payd == false) {
                    return Html::a('<span class="text-danger">Не оплачена</span>', '#', ['class'=>'cardio-pay', 'value'=>$model->id]);
                } elseif ($model->is_payd && $model->is_end == false) {
                    return '<span class="text-warning">В работе</span>';
                } elseif ($model->is_payd && $model->is_end) {
                    return '<span class="text-success">Выполнена</span>';
                }
            }
        ],
        [
            'attribute'=>'created_at',
            'value'=>function ($model) {
                return date('d.m.Y г.', $model->created_at);
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{cardio-delete} {view}',
            'buttons'=>[
                'cardio-delete'=>function($url, $model) {
                    return ($model->is_payd == false) ? Html::a('<span class="glyphicon glyphicon-trash"></span>', '#', ['class'=>'delete', 'title'=>'Удалить', 'pjax-container-id'=>'patient-cardio-pjax', 'url'=>$url]) : null;
                },
                'view'=>function($url, $model) {
                    return ($model->is_payd && $model->is_end) ? Html::a('Просмотр результатов', $url, ['class'=>'btn btn-xs btn-success']) : null;
                }
            ]
        ]
    ]
]);

$this->registerJs('
    $(document).on("click", ".delete", function(e) {
        var pjax_id = $(this).attr("pjax-container-id");
        var url = $(this).attr("url");   

        $.confirm({
            buttons: {
                confirm: {
                    action: function () {
                        ajaxAction(url, pjax_id)
                    },
                    btnClass: "btn-primary",
                    text: "да"
                },
                cancel: {
                    text: "нет"
                }
            },
            content: "Вы уверены, что хотите удалить данный элемент?",
            theme: "modern",
            title: "Внимание!"              
        });          
        e.preventDefault();
    });
    $(document).on("click", ".cardio-pay", function(e) {
        var cardio_id = $(this).attr("value");

        $.ajax({
            data: { cardio_id: cardio_id },
            method: "post",        
            url: "/cardio/cardio-pay"
        });
        e.preventDefault();
    });
');