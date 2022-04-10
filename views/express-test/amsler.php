<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест Амслера';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/10.jpg', ['class'=>'img-responsive']), '/img/express-test/10.jpg', ['class'=>'btn-magnific']) ?>
    </div>

    <div class="col-md-12">   
        <?= Html::tag('p', 'Простой тест Амслера дает возможность быстрого тестирования зрения на макулодистрофию глаза, патологий центральной части сетчатки глаза, последствий сахарного диабета.', 
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= Html::tag('p', 'Как проводится тест:<br/>'
                . '1. Разместите экран с решеткой (сеткой) Амслера на расстоянии 30 см от глаз.<br/>'
                . '2. Прикройте ладонью (не надавливая) один глаз и смотрите другим глазом на точку несколько секунд.<br/>'
                . '3. Закройте глаз, которым смотрели, откройте прикрытый глаз и смотрите на точку в центре также несколько секунд.<br/>',
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= $form->field($model, 'answer')->radioList([
            1=>'Линии ровные, без искажений на всем изображении, линии не размыты',
            2=>'Линии сетки не ровные, искажены, размыты',
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
            result = "<span style=\"color: green;\">Патология сетчатки глаз отсутствует.</span>";
        } else if (answer == 2) {
            result = "<span style=\"color: orangered;\">Вам следует немедленно обратиться к офтальмологу для прохождения обследования: Это может быть симптомом аномального изменения сетчатки.</span>";
        } else {
            result = "";
        }
        
        result += "<br/><span style=\"color: orange;\">Важно: Проходите этот тест время от времени!</span>";
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');

