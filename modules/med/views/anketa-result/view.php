<?php
use yii\helpers\Html;

$this->title = $user->fullname;
$this->params['breadcrumbs'][] = ['label'=>'Анкетирование', 'url'=>['/med/anketa/index']];
$this->params['breadcrumbs'][] = ['label'=>'Заполненные анкеты', 'url'=>['index', 'anketa_id'=>$anketa->id]];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-success btn-print'])) ?>

<?= Html::beginTag('div', ['id'=>'printDiv']) ?>

<?= Html::tag('div', 
    $this->render('_part/user', [
        'anketa'=>$anketa,
        'user'=>$user,
        'date'=>$date
    ]), ['id'=>'info']) ?>

<?= $this->render('_part/question', [
    'session_id'=>$session_id,
    'questions'=>$questions,
    'max_answer_count'=>$max_answer_count
]) ?>

<?= Html::endTag('div') ?>

<?php 
$this->registerCss('
@media print {
    body {
        font-family: "Times New Roman";
    }
}
');
$this->registerJs('
$(document).on("click", ".btn-print", function() {
    var printContents = document.getElementById("printDiv").innerHTML;
    var originalContents = document.body.innerHTML;    
    
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
});  
');