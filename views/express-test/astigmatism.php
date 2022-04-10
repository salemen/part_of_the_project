<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест на астигматизм';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/07.jpg', ['class'=>'img-responsive']), '/img/express-test/07.jpg', ['class'=>'btn-magnific']) ?>
    </div>

    <div class="col-md-12">   
        <?= Html::tag('p', 'Тест на Астигматизм помогает выявить насколько неправильную (не сферичную) форму приобрела роговица (а в редких случаях и хрусталик глаза).', 
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= Html::tag('p', 'Как проводится тест:<br/>'
                . '1. Расположитесь на расстоянии 50 – 100 см от монитора.<br/>'
                . '2. Прикройте один глаз листком бумаги либо ладонью.<br/>'
                . '3. Задержитесь взглядом на центре окружности буквально несколько секунд.<br/>'
                . '4. Проделайте с другим глазом эту же процедуру.<br/>',
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= $form->field($model, 'answer')->radioList([
            1=>'Линии одинаковой толщины, четкие без изгибов',
            2=>'Видны утолщения, изгибы, нечеткость вертикальных/горизонтальных линий',
        ])->label(false)->error(false) ?>

        <?= Html::tag('div', Html::label('Результат:') . Html::tag('div', null, ['id'=>'result']), ['class'=>'form-group']) ?>

        <?= Html::submitButton('Узнать результат', ['class'=>'btn btn-primary', 'style'=>'margin-right: 3px;']) ?>

        <?= Html::a('Сохранить результат в личном кабинете', ['/site/save-to-profile'], [
            'class'=>'btn btn-default btn-modal',
            'id'=>'profile-save',
            'style'=>'display: none;'
        ]) ?>
    </div>
</div> 
<?php $form->end() ?>
    
<?php    
$this->registerJs('
iCheckInit();

$("form").on("beforeSubmit", function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        var answer = $(".checked input[name=\"DynamicModel[answer]\"]").val();
        var result;
        
        if (answer == 1) {
            result = "<span style=\"color: green;\">Поздравляем, ваше зрение в порядке.</span>";
        } else if (answer == 2) {
            result = "<span style=\"color: orangered;\">Есть вероятность астигматизма.</span><br/>" +
                "<span style=\"\color: orange;\">Вам стоит обратится к специалисту-офтальмологу, обладающего специальной диагностической аппаратурой, что поможет установлению точного диагноза.<br/>" +
                    "Если даже в очках вы замечаете различия в линиях, вам следует проверить ваши очки, так как нескорректированный астигматизм значительно снижает остроту зрения.<br/></span>" +
                "<span style=\"color: green;\">В нашей клинике имеются все возможности для высокоточной диагностики и эффективного лечения нарушений рефракции, в том числе астигматизма как у детей, так и у взрослых по доступным ценам. Доверяйте профессионалам!</span>";
        } else {
            result = "";
        }
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');