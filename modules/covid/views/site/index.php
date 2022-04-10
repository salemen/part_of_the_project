<?php
use yii\helpers\Html;

$this->title = 'Ситуационный центр COVID-19';

function renderItem($imgSrc, $text, $linkUrl, $target = '_self')
{
    $btnText = Html::img($imgSrc, ['class' => 'img-center-responsive']) . Html::tag('p', $text);
    return Html::tag('div', Html::a($btnText, $linkUrl, ['target' => $target]), ['class' => 'item']);
}


$vaccineImg = Html::img('/sars/images/vaccine_horizontal.jpg', ['class'=>'img-responsive img-bordered hidden-xs']) ;
$vaccineImgg = Html::img('/sars/images/covidcon.png', ['class'=>'img-responsive img-bordered hidden-xs']) ;
$vaccineImgmini = Html::img('/sars/images/vaccine_vertical2.jpg') ;
$vaccineImgmini2 = Html::img('/sars/images/vaccine_vertical.jpg') ;
$vaccina = Html::img('sars/icon/vaccination.jpeg') ;


?>

<div class="text-center">
    <?= Html::tag('h1', $this->title, ['class'=>'text-primary']) ?>
    <div class="flex-row">
        <?php
            echo renderItem('/sars/icon/faq.png', 'Актуальные вопросы и ответы', ['/covid/faq']);
            echo renderItem('/sars/icon/info.png', 'Официальная информация', ['/covid/info']);
//            echo renderItem('/sars/icon/hotline.png', 'Горячая линия COVID-19', ['/covid/hotline']);
//            echo renderItem('/sars/icon/library.png', 'Библиотека COVID-19', ['/covid/library']);
            echo renderItem('/sars/icon/diagnosis.png', 'Диагностика COVID-19', ['/covid/diagnosis']);
            echo renderItem('/sars/icon/consult.png', 'Консультация по COVID-19', ['/doctor-special'], '_blank');
//            echo renderItem('/sars/icon/hospital.png', 'Госпитализация', ['/covid/hospital']);
            //echo renderItem('/sars/icon/pills.png', 'Лекарственное обеспечение', '#');
            //echo renderItem('/sars/icon/vaccine.png', 'Вакцинация', '#');
            echo renderItem('/sars/icon/monitor.png', 'Наблюдение онлайн', ['/monitor'], '_blank');
        echo renderItem('sars/icon/vaccination.png', 'Вакцинация', 'https://covid.0323.ru/vaccination', '_blank');


        ?>
    </div>
    <div id="carousel-example-generic" class="carousel slide " data-ride="carousel" data-interval="4000">
    <div class="carousel-inner">
    <div class="item active" style="margin-bottom: 15px;">

        <?= Html::a($vaccineImg, 'https://covid.0323.ru/vaccination', ['target'=>'_blank']) ?>
        <?= Html::a($vaccineImgmini2, 'https://covid.0323.ru/vaccination', ['class'=>'img-responsive hidden-sm hidden-md hidden-lg']) ?>

    </div>
        <div class="item" style="margin-bottom: 15px;">

            <?= Html::a($vaccineImgg, 'https://0323.ru/doctor', ['target'=>'_blank']) ?>
            <?= Html::a($vaccineImgmini, 'https://0323.ru/doctor', ['class'=>'img-responsive hidden-sm hidden-md hidden-lg']) ?>

        </div>
    </div>
    </div>

</div>

<?php
$this->registerCss('
.flex-row {
    display: flex;
    flex-flow: row wrap;
    justify-content: center;
    margin-top: 0%;
}
.item { 
    flex: 0 1 calc(30% - 12px);
    margin: 6px 6px 40px;
    text-align: center;
}
.item > a > img { 
    max-width: 80%;
}
.item > a > p {
    color: #777777;
    font-size: 20px;
    text-transform: uppercase;
}

@media (max-width: 767px) {
    .item { 
        flex: 0 1 calc(90% - 12px);
        margin: 6px 6px 20px;
        text-align: center;
    }
}    
'); ?>
