<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест близорукости/дальнозоркости';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/08.jpg', ['class'=>'img-responsive']), '/img/express-test/08.jpg', ['class'=>'btn-magnific']) ?>
    </div>

    <div class="col-md-12">   
        <?= Html::tag('p', 'Для проверки наличия близорукости (-) или дальнозоркости (+) используют данный дуохромный тест.', 
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= Html::tag('p', 'Как проводится тест:<br/>'
                . '1. Перед началом теста наденьте (если носите) очки или линзы.<br/>'
                . '2. Прикройте (рукой без надавливания) левый глаз и посмотрите на строку теста которую видите слева и справа, затем повторите тоже с другим глазом.<br/>',
            ['style'=>'margin-top: 10px;']) ?>
        
        <?= $form->field($model, 'answer')->radioList([
            1=>'Буквы кажутся более четкими на зеленом фоне',
            2=>'Буквы кажутся более четкими на красном фоне',
            3=>'Буквы одинаковы с обеих сторон'
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
            result = "Возможно наличие дальнозоркости (+).";
        } else if (answer == 2) {
            result = "Возможно наличие близорукости (-).";
        } else if (answer == 3) {
            result = "<span style=\"color: green;\">Зрение по тесту близорукости/дальнозоркости в норме.</span>";
        } else {
            result = "";
        }
        
        result += "<br/><span style=\"\color: orange;\">Важно! Более точную проверку зрения и диагностику может сделать только офтальмолог!</span>";

        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');

