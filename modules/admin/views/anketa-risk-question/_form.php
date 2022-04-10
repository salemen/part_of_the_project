<?php
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use kartik\select2\Select2;
use app\models\anketa\AnketaAnswer;
use app\models\anketa\AnketaRiskGroup;
use app\models\anketa\AnketaRiskQuestion;
use app\models\anketa\AnketaQuestion;

$anketa_id = AnketaRiskQuestion::getAnketaId($group_id);
$category_id = AnketaRiskQuestion::getCategoryId($group_id);
$group_type = AnketaRiskGroup::find()->select('type')->where(['id'=>$group_id])->scalar();
$select2Options = [
    'allowClear'=>true,
    'minimumResultsForSearch'=>'-1',
    'placeholder'=>'Укажите ответ',
    'width'=>'100%'
];

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Категории рисков', 'url'=>['anketa-risk-category/index', 'anketa_id'=>$anketa_id]];
$this->params['breadcrumbs'][] = ['label'=>'Группы рисков', 'url'=>['anketa-risk-group/index', 'category_id'=>$category_id]];
$this->params['breadcrumbs'][] = ['label'=>'Вопросы', 'url'=>['index', 'group_id'=>$group_id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin(['id'=>'anketa-risk-form']) ?>

<?= $form->field($model, 'question_id')->widget(Select2::classname(), [
        'data'=>ArrayHelper::map(AnketaQuestion::find()->where(['anketa_id'=>$anketa_id])->all(), 'id', 'name'),
        'hideSearch'=>false,
        'options'=>['placeholder'=>'Укажите вопрос'],
        'pluginOptions'=>['allowClear'=>true]
]) ?>

<?= ($group_type != AnketaRiskGroup::TYPE_SUM) ? $form->field($model, 'answer_id')->widget(Select2::classname(), [
    'data'=>ArrayHelper::map(AnketaAnswer::find()->where(['question_id'=>$model->question_id])->all(), 'id', 'name'),
    'options'=>$select2Options,
    'pluginOptions'=>['allowClear'=>true]
]) : null ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'group_id'=>$model->group_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
$(document).on("change", "#anketariskquestion-question_id", function() {
    var question_id = $(this).val();
    var select = $("#anketariskquestion-answer_id");
    select.val(null);
    select.empty();
    
    $.ajax({
        url: "/admin/anketa-risk-question/question-answers",
        method: "post",
        data: {question_id: question_id},
        success: function (result) {   
            var select2Options = ' . json_encode($select2Options) . ';
            select2Options.data = result.data;
            select.select2(select2Options);
        }
    });
});
') ?>


