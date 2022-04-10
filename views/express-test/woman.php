<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Экспресс-тесты: Насколько Вы привлекательны как женщина?';
$this->params['breadcrumbs'][] = 'Экспресс-тесты';
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="col-md-8 col-md-offset-2">
        <?= Html::a(Html::img('/img/express-test/06.jpg', ['class'=>'img-responsive']), '/img/express-test/06.jpg', ['class'=>'btn-magnific']) ?>
    </div> 

    <div class="col-md-12">   
        <?= Html::tag('p', 'Просто присмотритесь повнимательнее и выберите ту картинку, которая вам по душе.', ['style'=>'margin-top: 10px;']) ?>

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
            result = "Мисс `Спонтанность`<br>Неугомонная, шаловливая как ребенок и озорная. Ее глаза смеются, а характер так и бьет маленькими искорками. За этот блеск в глазах в нее и влюбляются!";
        } else if (answer == 2) {
            result = "Мисс `Хозяйка`<br>Ее главные черты — забота и тепло. Рядом с такими женщинами чувствуешь себя как дома. Мужчины это ценят, а сами барышни с удовольствием окружают своего избранника атмосферой уюта.";
        } else if (answer == 3) {
            result = "Мисс `Изюминка`<br>В этой девушке есть нечто особенное, что не поддается описанию. Она не такая как все и не подчиняющаяся рамкам. Каждый день с ней полон новизны и приключений.";
        } else if (answer == 4) {
            result = "Мисс `Вызов`<br>От таких как она закипает кровь. Так просто эти женщины не даются, их нужно добиваться и завоевывать. Правда, решаются на это только самые смелые — остальные предпочитают любоваться на расстоянии.";
        } else if (answer == 5) {
            result = "Мисс `Скромница`<br>Самая главная ценность этой категории девушек — они не имеют завышенных притязаний. Такие женщины довольны и тем, что есть, от всего сердца считая, что лучше синица в руке чем журавль в небе.";
        } else if (answer == 6) {
            result = "Мисс `Романтика`<br>Это — редкое сочетание очарования, воздушности и нежности. Ни капли агрессии, ни капли давления. Такая девушка искренне воспримет любой красивый жест и поступок. Все, что им нужно — любовь и взаимность.";
        } else if (answer == 7) {
            result = "Мисс `Железная леди`<br>Сильные стороны этой девушки — незаурядный ум, четкие ориентиры и независимость. Она не будет ждать принца. Скорее сама найдет мужчину, чтобы сделать из него такового. И, хотя такой подход не всегда хорош, слез от железной леди мужчина не дождется.";
        } else if (answer == 8) {
            result = "Мисс `Чувственность`<br>Такая женщина не считает, что мужчина должен быть единственным инициатором в спальне и не сомневается перед тем, как сделать первый шаг. Мужчины боготворят таких девушек, потому что она заставляет их чувствовать себя желанными.";
        } else {
            result = "";
        }
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');