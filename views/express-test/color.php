<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест на цветовосприятие';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/09.jpg', ['class'=>'img-responsive']), '/img/express-test/09.jpg', ['class'=>'btn-magnific']) ?>
    </div>

    <div class="col-md-12">   
        <?= Html::tag('p', 'Различие цветовых ощущений происходит из-за возможных дисфункций колбочковых клеток сетчатки глаза и тогда человек видит не весь спектр цвета, а лишь некоторые цвета.', 
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= Html::tag('p', 'Существует тест на цветовосприятие, просто посчитайте сколько цветов Вы видите на изображении.',
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= $form->field($model, 'answer')->radioList([
            1=>'20 цветов или меньше',
            2=>'20–32 цвета',
            3=>'32-39 цвета'
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
            result = "Так видят дихроматы (четвертая часть населения).";
        } else if (answer == 2) {
            result = "Так видят трихроматы (половина населения), они могут различать оттенки основных цветов.";
        } else if (answer == 3) {
            result = "Так видят тетрахроматы (четвертая часть населения)";
        } else {
            result = "";
        }
        
        result += "<br/><span style=\"color: orange;\">Для более точной диагностики цветовосприятия следует записаться на прием к офтальмологу.</span>";
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');

