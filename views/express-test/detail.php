<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест на внимательность к деталям';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/02.jpg', ['class'=>'img-responsive']), '/img/express-test/02.jpg', ['class'=>'btn-magnific']) ?>
    </div> 

    <div class="col-md-12">   
        <?= Html::tag('p', 'Данная иллюзия поможет вам ответить на вопрос, насколько вы внимательны. Она даст подсказки, внимательны ли вы к деталям или видите общую картину.<br>Итак, что вы увидели первым?', ['style'=>'margin-top: 10px;']) ?>

        <?= $form->field($model, 'answer')->radioList([
            1=>'Пожилая пара',
            2=>'Три человека'
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
        var resultImg;
        var imgFile;
        
        if (answer == 1) {
            imgFile = "/img/express-test/02-0.jpg";
            result = "Если вы в первую очередь увидели пожилую пару, вы смотрите на ситуацию в целом.  " + 
            "Вы не застреваете на мелочах, и всегда видите картину с высоты птичьего полета.<br>" +
            "По натуре вы стратег и можете стать прекрасным управляющим. Вы умеете планировать, и вас не волнуют мелкие проблемы.";
        } else if (answer == 2) {
            imgFile = "/img/express-test/02-1.jpg";
            result = "Если вы в первую очередь увидели трех людей: двоих мужчин на переднем плане и женщину на заднем фоне, то вы обладаете " +
                "невероятной внимательностью к деталям. Вы обращаете внимание на нюансы, о существовании которых другие могут не подозревать.<br>" +
                "Вы скрупулезны, и от вашего взора мало что может ускользнуть. Вы относитесь к людям, которые очень хорошо умеют планировать дела " +
                "до мельчайших подробностей. Одним словом, вы  предпочитаете узнавать все детально, а не поверхностно.";
        } else {
            result = "";
        }
        
        resultImg = "<div class=\"row\"><div class=\"col-md-4\"><img src=" + imgFile + " class=\"img-responsive\" style=\"margin-top: 10px;\"></div></div>";
        
        $("#result").html("<b>" + result + "<br>" + resultImg + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');