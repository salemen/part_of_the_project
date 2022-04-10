<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use app\models\test\Test;
use app\models\test\TestQuestion;
use app\models\zung\ZungQuestions;

$this->title = 'Тестирование';
$this->params['breadcrumbs'][] = ['label'=>'Тесты', 'url'=>['index', 'test_id'=>$test_id]];
$this->params['breadcrumbs'][] = $this->title;

$is_zung = ($test_id == Test::ZUNG_TEST_ID);
?>

<div class="row>">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div class="jumbotron"> 
                Прочитайте внимательно каждое из приведенных ниже предложений и
                выберите соответствующий ответ в зависимости от того, как Вы себя 
                чувствуете в последнее время. Над вопросами долго не задумывайтесь,
                поскольку правильных или неправильных ответов нет.
            </div>
            <?php $form = ActiveForm::begin() ?>
            <?php foreach ($questions as $i=>$question) { $num = $i+1;?>
                <div style="border: 1px solid #ccc; border-radius: 4px; padding: 10px;">
                    <b><?= ($num) . '. ' . (($is_zung) ? $question->question : $question->name) ?></b>
                    <?= $form->field($model, "answer[" . (($is_zung) ? $num : $question->id) . "]")
                    ->radioList(($is_zung) ? ZungQuestions::getAnswerList() : TestQuestion::getAnswerList($question->id))->label(false)->error(false) ?>
                </div>
                <br>
            <?php } ?>
            <div class="row">
                <div class="col-md-6 col-md-offset-3" style="text-align: center">
                    <?= Html::submitButton('Сохранить данные', ['class'=>'btn btn-lg btn-success btn-submit']) ?> 
                </div>
            </div>
            <?php ActiveForm::end() ?>
        </div>            
    </div>
</div>

<?php
$this->registerJs('
iCheckInit();    
$("form").submit(function (e) {
    var data = $(this).data("yiiActiveForm");
    if (data.validated) {
        $(".btn-submit").text("Обработка результатов...");
        $(".btn-submit").attr("disabled", "disabled");
        return true;
    }
});
');