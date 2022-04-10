<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;
use app\models\anketa\AnketaRiskGroup;

$this->title = 'Группы рисков';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Категории рисков', 'url'=>['anketa-risk-category/index', 'anketa_id'=>AnketaRiskGroup::getAnketaId($category_id)]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'category_id'=>$category_id], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],
        'risk_name',
        'tactic',
        [
            'attribute'=>'sex',
            'value'=>function($model) {
                switch ($model->sex) {
                    case null: return 'Для всех';
                    case 0: return 'Для женщин';
                    case 1: return 'Для пужчин';
                }
            }
        ],
        [
            'attribute'=>'type',
            'value'=>function($model) {
                switch ($model->type) {
                    case AnketaRiskGroup::NOTYPE: return 'Без типа';
                    case AnketaRiskGroup::TYPE_AND: return 'И';
                    case AnketaRiskGroup::TYPE_OR: return 'Или';
                    case AnketaRiskGroup::TYPE_SUM: return 'Сумма баллов';
                }
            }
        ],
        'value',
        [
            'attribute'=>'operator',
            'value'=>function($model) {
                switch ($model->operator) {
                    case '==': return '=';
                    case '!=': return '≠';
                    case '>': return '>';
                    case '<': return '<';
                }
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'header'=>'Вопросы',
            'template'=>'{anketa-risk-question}',
            'buttons'=>[
                'anketa-risk-question'=>function ($url, $model) {
                    return Html::a('<i class="glyphicon glyphicon-pencil" aria-hidden="true"></i>', ['anketa-risk-question/index', 'group_id'=>$model->id], []);
                }
            ]
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>
