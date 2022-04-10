<?php
use yii\helpers\Html;

$this->title = 'Заполнение анкеты';
$this->params['breadcrumbs'][] = ['label'=>'Анкетирование', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-8 col-md-offset-2" style="margin-top: 30px;">
        <?= Html::tag('div', null, ['id'=>'anketa-progress']) ?>  
        <?= Html::tag('div', null, [
            'class'=>'box box-body box-primary',
            'data'=>[
                'anketa_id'=>$anketa->id,
                'session_id'=>$session->id,
                'count'=>$count,
                'prev_question'=>$prev_question
            ],
            'id'=>'anketa-content'
        ]) ?>        
</div>

<?php
$this->registerJs('
$(document).ready(function() {    
    var content = $("#anketa-content");
    var progress = $("#anketa-progress");
    
    progress.load("get-progress", {
        count: content.attr("data-count"),
        prev_question: content.attr("data-prev_question")
    });
    
    content.load("get-form", {
        anketa_id: content.attr("data-anketa_id"),
        session_id: content.attr("data-session_id"),
        prev_question: content.attr("data-prev_question")
    });
});    
');