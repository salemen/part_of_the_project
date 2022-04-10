<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Администрирование: Тесты';
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create'], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'name',
        'desc',     
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Группы',
            'template'=>'{test-group}',
            'buttons'=>[
                'test-group'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['test-group/index', 'test_id'=>$model->id], []);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>

