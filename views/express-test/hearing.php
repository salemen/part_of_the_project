<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Оценка слуха';
$this->params['breadcrumbs'][] = $this->title;
?>
    
<?php $form = ActiveForm::begin(['id'=>'index-form']) ?>

<div class="row"> 
    <div class="box box-body box-primary">
        <div class="col-md-6">
            <video id="hearingVideo" controls controlsList="nodownload" disablepictureinpicture src="/img/express-test/hearing.mp4" height="auto" width="100%"></video>  
        </div> 
        <div class="col-md-6">   
            <?= Html::tag('p', 'Чтобы провести оценку слуха, запустите видео и слушайте. Начало услышанного писка на шкале и есть показатель слуха.', ['style'=>'margin-top: 10px;']) ?>

            <?= $form->field($model, 'answer')->radioList([
                1=>'160-100',
                2=>'80-60',
                3=>'60-40',
                4=>'40-20',
                5=>'20-0'
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
</div> 
<?php $form->end() ?>
    
<?php
$this->registerCss('
video::-webkit-media-controls-timeline { display: none !important; }
');
$this->registerJs('
iCheckInit();

$("form").on("beforeSubmit", function (e) {
    var data = $(this).data("yiiActiveForm");
    
    if (data.validated) {
        var answer = $(".checked input[name=\"DynamicModel[answer]\"]").val();
        var result;
        
        if (answer == 1) {
            result = "Отлично.";
        } else if (answer == 2) {
            result = "Хорошо.";
        } else if (answer == 3) {
            result = "Не плохо.";
        } else if (answer == 4) {
            result = "Плохо.";
        } else if (answer == 5) {
            result = "Ужасно.";
        } else {
            result = "";
        }
        
        $("#result").html("<b>" + result + "</b>");
        
        $("#profile-save").css("display", "inline-block");
    }
    
    return false;
}); 
');