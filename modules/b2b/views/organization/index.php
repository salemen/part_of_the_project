<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\models\data\OrganizationBank;

$this->title = 'Организации';
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'before' => $user->identity->roles->is_santal ? null : Html::a('Добавить', ['create'], ['class' => 'btn btn-success'])
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
        'name',
        'city',
        'inn',
        'kpp',
        'ogrn',
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Банк. реквизиты',
            'template' => '{bank}',
            'buttons' => [
                'bank' => function ($url, $model) {
                    $class = OrganizationBank::isExists($model->id) ? 'btn-info' : 'btn-danger';
                    $btn = Html::button('Редактировать', ['class' => "btn btn-xs $class"]);
                    return Html::a($btn, $url);
                }
            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'header' => 'Подразделения',
            'template' => '{departments}',
            'buttons' => [
                'departments' => function ($url, $model) {
                    $btn = Html::button('Редактировать', ['class' => 'btn btn-info btn-xs']);
                    return Html::a($btn, ['/b2b/department/index', 'org_id' => $model->id]);
                }
            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => $user->identity->roles->is_santal ? '{view}' : '{update} {view} {delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-trash"></span>', ['delete', 'id' => $model->id], [
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить организацию? Внимание, нажимая кнопку "Да" все данные об организации и сотрудники этой организации будут автоматически удалены.',
                            'method' => 'post'
                        ],
                        'title' => 'Удалить'
                    ]);
                }
            ]
        ]
    ]
]) ?>