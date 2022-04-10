<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Справочник: Сотрудники';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>($is_official ? null : Html::a('Добавить', ['create'], ['class'=>'btn btn-success'])) . Search::widget(['action'=>[Yii::$app->controller->action->id, 'is_santal'=>$is_santal, 'is_official'=>$is_official], 'model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'fullname',
            'header'=>'Сотрудник'
        ],
        [
            'attribute'=>'phone',
            'header'=>'Номер телефона: личный / служебный',
            'value'=>function ($model) {
                return ($model->phone ? : '-') . ' / ' . ($model->phone_work ? : '-');
            }
        ],        
        'email',
        'city',
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>$is_official ? '{employee_custom} {view}' : '{update} {view}',
            'buttons'=>[                
                'change-password'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-lock"></span>', $url, ['title'=>'Изменить пароль']);
                },
                'employee_custom'=>function($url, $model) {
                    return Html::a('<span class="glyphicon glyphicon-star"></span>', ['/admin/employee-custom/index', 'employee_id'=>$model->id], ['title'=>'Специальные регалии сотрудника']);
                },
            ]
        ]
    ]
]) ?>