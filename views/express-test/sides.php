<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест на определение сильных и слабых сторон';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/05.jpg', ['class'=>'img-responsive']), '/img/express-test/05.jpg', ['class'=>'btn-magnific']) ?>
    </div> 

    <div class="col-md-12">   
        <?= Html::tag('p', 'Выберите фигуру на рисунке и узнайте свои сильные стороны.', ['style'=>'margin-top: 10px;']) ?>

        <?= $form->field($model, 'answer')->radioList([
            1=>'1',
            2=>'2',
            3=>'3',
            4=>'4',
            5=>'5',
            6=>'6',
            7=>'7',
            8=>'8'
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
            result = "Лидер<br>Ваши сильные стороны — умение руководить и хорошие организаторские способности. Вы также умеете красиво излагать свои мысли и способны поддержать дискуссию практически с любым собеседником. Ещё один ваш плюс — быстрая адаптация к окружающей ситуации.";
        } else if (answer == 2) {
            result = "Исполнитель<br>Сильные стороны вашей личности — ответственность и серьёзность. Вы также обладаете высокой самоорганизованностью, хорошо адаптируетесь к новым условиям, но вам тяжело самостоятельно принимать решения. Вы – прекрасный исполнитель, профессионал в своем деле. Всегда стараетесь любое дело выполнить хорошо и довести его до конца. Вас особенно ценят за трудолюбие, честность и надежность.";
        } else if (answer == 3) {
            result = "Мнительный<br>Ваше преимущество – врожденный талант ко многим вещам сразу. Вы очень разносторонняя личность и наделены большими возможностями.<br>" + 
                "Однако случается, что ваши ранимость и чувствительность дают вам почву для сомнений в своих силах. Вам всё время нужна моральная поддержка и подбадривание.";
        } else if (answer == 4) {
            result = "Ученый<br>Наиболее сильные стороны вашей личности — рационализм, развитое аналитическое мышление, собранность и внутреннее спокойствие. У вас всегда и по любому поводу есть свое мнение.<br>" + 
                "Вам нравится продумывать свои варианты развития событий и планировать какие-то действия. Именно такая расчётливость помогает вам в достижении немалых успехов.";
        } else if (answer == 5) {
            result = "Интуитивный<br>Самые яркие ваши преимущества — универсальность и умение с успехом выполнять несколько дел сразу. Вам также легко переключаться от одной деятельности к другой. У вас хорошая интуиция и отличное воображение. У вас неплохо получается контролировать свои эмоции.";
        } else if (answer == 6) {
            result = "Творец<br>Ваши сильные стороны — развитое воображение, изобретательность и принципиальность. У вас есть собственные четкие убеждения и моральные нормы, от которых вы не отступаете, поэтому вами сложно манипулировать. Вы склонны быстро увлекаться какой-то новой идеей и обязательно будете стараться довести ее до конца.";
        } else if (answer == 7) {
            result = "Эмоциональный<br>Ваша сила в умении сопереживать и помогать. Вас ценят именно за эти качества, так как вы всегда найдете доброе слово и утешите всех, кто придет к вам со своими проблемами.<br>" + 
                "Однако иногда вы становитесь заложником собственной эмоциональности, так как именно она заставляет вас совершать ошибки и необдуманные поступки.";
        } else if (answer == 8) {
            result = "Манипулятор<br>Ваша сильная сторона – умение манипулировать людьми. Вы умеете заставить других делать всё, что нужно вам. Во многих ситуациях вы проявляете себя как жесткий и принципиальный человек, однако именно эти качества позволяют вам оказывать давление на людей и добиваться от них нужной реакции.";
        } else {
            result = "";
        }
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');