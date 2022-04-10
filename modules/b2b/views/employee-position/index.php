<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title = 'Место работы';
$this->params['breadcrumbs'][] = ['label' => 'Сотрудники', 'url' => ['/b2b/employee/index']];
$this->params['breadcrumbs'][] = $this->title;
$user = Yii::$app->user;
?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'panel' => [
        'before' => $user->identity->roles->is_santal ? null : Html::a('Добавить', ['create', 'employee_id' => $employee->id], ['class' => 'btn btn-success'])
    ],
    'columns' => [
        ['class' => 'kartik\grid\SerialColumn'],
        [
            'attribute' => 'org_id',
            'value' => function ($model) {
                return (isset($model->org) ? $model->org->name : null);
            }
        ],
        'empl_dep',
        'empl_pos',
        [
            'class' => 'kartik\grid\ActionColumn',
            'template' => '{update} {delete}',
            'visible' => !$user->identity->roles->is_santal
        ]
    ]
]) ?>