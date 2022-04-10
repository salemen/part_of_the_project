<?php
use kartik\grid\GridView;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use app\models\employee\Employee;
use app\models\user\UserDocs;

$this->title = 'Менеджер: Консультанты 0323.ru';
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
            'format' => 'raw',
            'value' => function ($model) {

                $user_doc = UserDocs::findOne(['user_id' => $model->id, 'doc_ext' => 'pdf', 'doc_name' => 'Договор-оферта']);
                $link = '';
                if ($user_doc) {
                    $link = '/uploads/' . $user_doc->doc_file;
                }

                $name = ($model) ? $model->fullname : '-';
                if ($link) {
                    $name = '<a href="' . $link . '" target="_blank" style="text-decoration: underline;">' . $name . '</a>';
                }

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
            'template' => '{switch}',
            'header' => 'Коснультант',
            'buttons' => [
                'switch' => function ($url, $model) {
                    if ($model->activity == 1) {
                        return '<a class="btn btn-block btn-danger" href="/admin/registration/off?id=' . $model->id . '">Откл.</a>';
                    }
                    if ($model->activity == 0) {
                        return 'Отключен<br/><a class="btn btn-block btn-success" href="/admin/registration/on?id=' . $model->id . '">Вкл.</a>';
                    }
                    if ($model->activity == -1) {
                        return '<b>Откл. админом</b> <br/><a class="btn btn-block btn-success" href="/admin/registration/on?id=' . $model->id . '">Вкл.</a>';
                    }
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
                    return Html::a('Удалить', ['delete', 'id' => $model->id], [
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