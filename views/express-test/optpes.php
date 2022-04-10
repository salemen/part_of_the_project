<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Оптимист или пессимист?';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/03.jpg', ['class'=>'img-responsive']), '/img/express-test/03.jpg', ['class'=>'btn-magnific']) ?>
    </div> 

    <div class="col-md-12">   
        <?= Html::tag('p', 'Это иллюзия с кошкой на лестнице позволяет взглянуть на нее с разных точек зрения.<br>Ответьте на вопрос: эта кошка поднимается наверх или спускается вниз?', ['style'=>'margin-top: 10px;']) ?>

        <?= $form->field($model, 'answer')->radioList([
            1=>'Кошка поднимается наверх',
            2=>'Кошка спускается вниз'
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
            result = "Если вам кажется, что кошка поднимается наверх, то вы, скорее всего, оптимист. Вы видите возможности и развитие, где бы вы ни были.<br>" + 
                "Вы привыкли искать пути, которые помогут вам подняться в жизни, и в ситуации, где перед вами стоит выбор подняться выше остальных или " +
                "упасть до их уровня, вы всегда будете лучшим. Вы амбициозны, и никто кроме вас не остановит вас от достижений в жизни.";
        } else if (answer == 2) {
            result = "Если вам показалось, что кошка спускается вниз, вы немного пессимистичны по натуре. Вы скептик, если говорить начистоту. " +
                "Возможно, это связано с вашим опытом в жизни или людьми, которых вы встречали на своем пути.<br>" +
                "Это говорит о том, что вам нелегко доверять другим, вы просчитываете, прежде чем сделать шаг, и подозрительно относитесь к людям, которые слишком любезны.<br>" +
                "Несмотря на свою пессимистичность, вы намного проницательнее остальных, и вас практически невозможно обмануть.";
        } else {
            result = "";
        }
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');