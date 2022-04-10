<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Тест на степень утомляемости';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/01.jpg', ['class'=>'img-responsive']), '/img/express-test/01.jpg', ['class'=>'btn-magnific']) ?>
    </div> 

    <div class="col-md-12">   
        <?= Html::tag('p', 'Японский психотерапевт Ямамото Хашима разработал тест на степень утомляемости:', ['style'=>'margin-top: 10px;']) ?>

        <?= $form->field($model, 'answer')->radioList([
            1=>'Картинка не двигается',
            2=>'Движение среднее',
            3=>'Движение активное'
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
            result = "<span style=\"color: green;\">Стабильное психоэмоциональное состояние.</span>";
        } else if (answer == 2) {
            result = "<span style=\"\color: orange;\">Психоэмоциональное истощение, желателен санаторно-курортный отдых.</span>";
        } else if (answer == 3) {
            result = "<span style=\"color: orangered;\">Имеются опасные признаки психоза, неврастении и депрессии.</span>";
        } else {
            result = "";
        }
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');