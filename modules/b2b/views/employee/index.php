<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'before' => $user->identity->roles->is_santal ? null : Html::a('Добавить', ['create'], ['class' => 'btn btn-success', 'style' => 'margin-right: 3px;']) . Html::a('Загрузить списком', ['employee-import/xlsx'], ['class' => 'btn btn-warning'])
    ],
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'status',
            'format' => 'raw',
            'value' => function ($model) {
                if ($model->status == 10) {
                    $color = 'green';
                    $text = 'Активен';
                } else {
                    $color = 'orangered';
                    $text = 'Неактивен';
                }
                return Html::tag('span', $text, ['style' => ['color' => $color]]);
            }
        ],
        [
            'attribute' => 'fullname',
            'header' => 'Сотрудник'
        ],
        [
            'attribute' => 'phone',
            'header' => 'Номер телефона: личный / служебный',
            'value' => function ($model) {
                return ($model->phone ?: '-') . ' / ' . ($model->phone_work ?: '-');
            }
        ],
        'email',
        'city',
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Место работы',
            'template' => '{positions}',
            'buttons' => [
                'positions' => function ($url, $model) {
                    $btn = Html::button('Редактировать ' . '', ['class' => 'btn btn-info btn-xs']);
                    return Html::a($btn, ['/b2b/employee-position/index', 'employee_id' => $model->id]);
                }
            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => $user->identity->roles->is_santal ? '{view}' : '{update} {view} {delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    $user = Yii::$app->user;
                    if ($user->id != $model->id) {
                        return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                            'data' => [
                                'confirm' => 'Вы действительно хотите удалить сотрудника? Внимание, нажимая кнопку "Да" все данные сотрудника будут удалены безвозвратно. ',
                                'method' => 'post'
                            ],
                            'title' => 'Удалить'
                        ]);
                    } else {
                        return null;
                    }
                }
            ]
        ]
    ]
])
?>