<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\widgets\Search;
use app\models\anketa\AnketaAnswer;
use app\models\anketa\AnketaRiskGroup;
use app\models\anketa\AnketaRiskQuestion;
use app\models\anketa\AnketaQuestion;

$anketa_id = AnketaRiskQuestion::getAnketaId($group_id);
$category_id = AnketaRiskQuestion::getCategoryId($group_id);
$group_type = AnketaRiskGroup::find()->select('type')->where($group_id)->scalar();

$this->title = 'Вопросы';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Категории рисков', 'url'=>['anketa-risk-category/index', 'anketa_id'=>$anketa_id]];
$this->params['breadcrumbs'][] = ['label'=>'Группы рисков', 'url'=>['anketa-risk-group/index', 'category_id'=>$category_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= GridView::widget([
    'dataProvider'=>$dataProvider,
    'panel'=>[
        'before'=>Html::a('Добавить', ['create', 'group_id'=>$group_id], ['class'=>'btn btn-success']) . Search::widget(['model'=>$searchModel])
    ],
    'columns'=>[
        ['class'=>'kartik\grid\SerialColumn'],     
        [
            'attribute'=>'question_id',
            'value'=>function($model) use ($anketa_id) {
                return AnketaQuestion::find()->select('name')->where(['id'=>$model->question_id, 'anketa_id'=>$anketa_id])->scalar();
            }
        ],
        [
            'attribute'=>'answer_id',
            'value'=>function($model) use ($group_type, $anketa_id) {
                $question_type = AnketaQuestion::find()->select('type')->where(['id'=>$model->question_id, 'anketa_id'=>$anketa_id])->scalar();
                
                if ($question_type != AnketaQuestion::TYPE_MAIN && $question_type != AnketaQuestion::TYPE_OPEN) {
                    if ($group_type != AnketaRiskGroup::TYPE_SUM) {
                        return AnketaAnswer::find()->select('name')->where(['id'=>$model->answer_id, 'question_id'=>$model->question_id])->scalar();
                    }
                }
                
                return $model->answer_id;
            }
        ],
        [
            'class'=>'kartik\grid\ActionColumn',
            'template'=>'{update} {delete}'
        ]
    ]
]) ?>

