<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Категории рисков';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'anketa_id'=>$anketa_id], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'name',     
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Группы',
            'template'=>'{anketa-risk-group}',
            'buttons'=>[
                'anketa-risk-group'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['anketa-risk-group/index', 'category_id'=>$model->id], []);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>

