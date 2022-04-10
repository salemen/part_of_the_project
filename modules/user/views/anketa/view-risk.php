<?php
use yii\helpers\Html;

$this->title = 'Заключения по результатам';
$this->params['breadcrumbs'][] = ['label'=>'Мои анкеты', 'url'=>['/user/anketa']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?= Html::tag('p', Html::button('Распечатать', ['class'=>'btn btn-success btn-print'])) ?>

<?= Html::beginTag('div', ['id'=>'printDiv']) ?>

<?= $this->render('_part/risk', ['categories'=>$categories, 'session_id'=>$session_id, 'anketa_id'=>$anketa_id]) ?>

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