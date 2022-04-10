<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Тесты', 'url'=>['test/index']];
$this->params['breadcrumbs'][] = ['label'=>'Группы', 'url'=>['test-group/index', 'group_id'=>$group_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'group_id'=>$group_id], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'name',
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>