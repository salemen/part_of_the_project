<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\employee\Employee;

$this->title = 'Менеджер: Заявки на регистрацию';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'responsive' => false,
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'employee_id',
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'data' => ArrayHelper::map(Employee::find()->where(['status' => 10])->orderBy('fullname')->all(), 'id', 'fullname'),
                'hideSearch' => false,
                'options' => ['placeholder' => 'Фильтр'],
                'pluginOptions' => ['allowClear' => true]
            ],
            'header' => 'Сотрудник',
            'value' => function ($model) {
                $name = ($model) ? $model->fullname : '-';
                return $name;
            }
        ],
        [
            'attribute' => 'email',
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'data' => ArrayHelper::map(Employee::find()->where(['status' => 10])->orderBy('email')->all(), 'email', 'email'),
                'hideSearch' => false,
                'options' => ['placeholder' => 'Фильтр'],
                'pluginOptions' => ['allowClear' => true]
            ],
            'header' => 'Email',
            'value' => function ($model) {
                $name = ($model) ? $model->email : '-';
                return $name;
            }
        ],
        [
            'attribute' => 'phone',
            'header' => 'Телефон',
            'value' => function ($model) {
                $name = ($model) ? $model->phone : '-';
                return $name;
            }
        ],
        [
            'attribute' => 'city',
            'filterType' => GridView::FILTER_SELECT2,
            'filterWidgetOptions' => [
                'data' => ArrayHelper::map(Employee::find()->where(['status' => 10])->orderBy('city')->all(), 'city', 'city'),
                'hideSearch' => false,
                'options' => ['placeholder' => 'Фильтр'],
                'pluginOptions' => ['allowClear' => true]
            ],
            'header' => 'Город',
            'value' => function ($model) {
                $name = ($model) ? $model->city : '-';
                return $name;
            }
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{active}',
            'buttons' => [
                'active' => function ($url, $model) {
                    return '<a class="btn btn-block btn-success" href="/admin/registration/active?id=' . $model->id . '">Активировать</a>';
                }
            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{edit}',
            'buttons' => [
                'edit' => function ($url, $model) {
                    return '<a class="btn btn-block btn-primary" href="/admin/registration/edit?id=' . $model->id . '">Редактировать</a>';
                }
            ]
        ],
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{delete}',
            'buttons' => [
                'delete' => function ($url, $model) {
                    return Html::a('Удалить', ['delete_active', 'id' => $model->id], [
                        'class' => 'btn btn-danger',
                        'data' => [
                            'confirm' => 'Вы действительно хотите удалить?',
                            'method' => 'post',
                        ],
                    ]);
                },
            ]
        ]
    ]
]) ?>