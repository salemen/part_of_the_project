<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;

$this->title = 'Условия';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'anketa_id'=>$anketa_id], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel, 'action'=>['index', 'anketa_id'=>$anketa_id]])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        [
            'attribute'=>'param_name',
            'value'=>function($model) {
                switch ($model->param_name) {
                    case 'age': return 'Возраст';
                    case 'sex': return 'Пол';
                }
            }
        ],
        [
            'attribute'=>'value',
            'value'=>function($model) {
                switch ($model->param_name) {
                    case 'age': return $model->value;
                    case 'sex': return ($model->value) ? 'Мужчина' : 'Женщина';
                }
            }
        ],
        [
            'attribute'=>'operator',
            'value'=>function($model) {
                switch ($model->operator) {
                    case '>': return '>';
                    case '<': return '<';
                    case '!=': return '≠';
                    case '==': return '=';
                }
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>