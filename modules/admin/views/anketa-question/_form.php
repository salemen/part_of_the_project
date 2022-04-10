<?php
use app\models\anketa\AnketaAnswer;
use app\models\anketa\AnketaQuestion;
use kartik\select2\Select2;
use unclead\multipleinput\MultipleInput;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? 'Добавить' : 'Изменить';
$this->params['breadcrumbs'][] = ['label'=>'Администрирование: Анкеты', 'url'=>['anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Вопросы', 'url'=>['index', 'anketa_id'=>$model->anketa_id]];
$this->params['breadcrumbs'][] = $this->title;

$select2Options = [
    'allowClear'=>true,
    'minimumResultsForSearch'=>'-1',
    'placeholder'=>'Укажите родительский ответ (если условия для перехода к текущему вопросу нет, то оставьте поле пустым)',
    'width'=>'100%'
];
?>

<?php $form = ActiveForm::begin() ?>

<?= $form->field($model, 'is_skip')->checkbox() ?>

<?= $form->field($model, 'name')->textarea(['rows'=>4, 'style'=>'resize: vertical;']) ?>

<?= ($model->type != AnketaQuestion::TYPE_MAIN) ? $form->field($model, 'parent_id')->widget(Select2::classname(), [
    'data'=>ArrayHelper::map(AnketaQuestion::find()->where(['anketa_id'=>$model->anketa_id])->all(), 'id', 'name'),
    'hideSearch'=>false,
    'options'=>['placeholder'=>'Укажите родительский вопрос (оставьте поле пустым, если нет родителя)'],
    'pluginOptions'=>['allowClear'=>true]
]) : null ?>

<?= $form->field($model, 'parent_answer_id')->widget(Select2::classname(), [
    'data'=>ArrayHelper::map(AnketaAnswer::find()->where(['question_id'=>$model->parent_id])->all(), 'id', 'name'),
    'options'=>$select2Options,
    'pluginOptions'=>['allowClear'=>true]
]) ?>

<?php
if ($model->type == AnketaQuestion::TYPE_ONE || $model->type == AnketaQuestion::TYPE_MULTI) {
    echo $form->field($model, 'answers')->widget(MultipleInput::className(), [
        'min'=>($count && $count <= 10) ? $count : 1,
        'max'=>10,
        'columns'=>[
            [
                'name'=>'id',
                'type'=>'hiddenInput',
                'value'=>function($data) {
                    return $data['id'];
                }
            ],
            [
                'name'=>'name',
                'type'=>'textarea',
                'title'=>'Ответ',
                'enableError'=>true,
                'options'=>[
                    'rows'=>1,
                    'style'=>'resize: vertical;'
                ],
                'value'=>function($data) {
                    return $data['name'];
                }
            ],
            [
                'name'=>'cost',
                'title'=>'Балл',
                'enableError'=>true,
                'value'=>function($data) {
                    return ($data['cost'] !== null) ? $data['cost'] : null;
                }
            ]
        ]
    ])->label(false);
} ?>

<div class="form-group">
    <?= Html::submitButton('Сохранить', ['class'=>$model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    <?= Html::a('Отмена', ['index', 'anketa_id'=>$model->anketa_id], ['class'=>'btn btn-danger']) ?>
</div>

<?php ActiveForm::end() ?>

<?php
$this->registerJs('
iCheckInit();

$(document).on("change", "#anketaquestion-parent_id", function() {
    var parent_id = $(this).val();
    var select = $("#anketaquestion-parent_answer_id");
    select.val(null);
    select.empty();
    
    $.ajax({
        url: "/admin/anketa-question/parent-answers",
        method: "post",
        data: {parent_id: parent_id},
        success: function (result) { 
            var select2Options = ' . json_encode($select2Options) . ';
            select2Options.data = result.data;
            select.select2(select2Options);
        }
    });
});
') ?>