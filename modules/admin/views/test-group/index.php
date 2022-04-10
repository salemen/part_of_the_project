<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Группы';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Тесты', 'url'=>['test/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'test_id'=>$test_id], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'name',
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Вопросы',
            'template'=>'{test-question}',
            'buttons'=>[
                'test-question'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['test-question/index', 'group_id'=>$model->id], []);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>
