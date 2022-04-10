<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Справочник: Пациенты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'fullname',
            'header'=>'Пациент'
        ],
        'phone',
        'email',
        'city',
        [
            'attribute'=>'last_activity',
            'header'=>'Последняя активность',
            'value'=>function ($model) {
                return ($model->last_activity !== null) ? date('d.m.Y H:i', $model->last_activity) : '-';
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{password-reset} {view}',
            'buttons'=>[
                'password-reset'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, ['class'=>'password-reset', 'title'=>'Сбросить пароль']);
                }
            ]
        ]
    ]
]) ?>

<?php
$this->registerJs('
$(document).on("click", ".password-reset", function(e) {
    var url = $(this).attr("href");   

    $.confirm({
        buttons: {
            confirm: {
                action: function () {
                    $.ajax({
                        method: "post",
                        url: url
                    });
                },
                btnClass: "btn-primary",
                text: "да"
            },
            cancel: {
                text: "нет"
            }
        },
        content: "Вы уверены, что хотите сбросить пароль для данного пациента?",
        theme: "modern",
        title: "Внимание!"              
    });          
    e.preventDefault();
});
');