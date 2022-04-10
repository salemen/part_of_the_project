<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Официальная информация';
?>

<div class="row" style="margin-top: 2%;">
    <div class="col-md-12">
        <ul style="font-size: 22px; list-style-type: none; padding: 0;">
            <li style="margin-bottom: 10px;">
                <?= Html::a('<img src="/sars/org-logo/minzdrav-min.png">  Министерство здравоохранения Российской Федерации', 'https://covid19.rosminzdrav.ru', ['target'=>'_blank']) ?>
            </li>
            <li style="margin-bottom: 10px;">
                <?= Html::a('<img src="/sars/org-logo/rpn-min.png">  Федеральная служба по надзору в сфере защиты прав потребителей и благополучия человека', 'https://www.rospotrebnadzor.ru/about/info/news_time/news_details.php?ELEMENT_ID=13566', ['target'=>'_blank']) ?>
            </li>
            <li style="margin-bottom: 10px;">
                <?= Html::a('<img src="/sars/org-logo/stop-min.png">  Стопкоронавирус.РФ', 'https://xn--80aesfpebagmfblc0a.xn--p1ai', ['target'=>'_blank']) ?>
            </li>
            <li style="margin-bottom: 10px;">
                <?= Html::a('<img src="/sars/org-logo/voz-min.png">  Всемирная организация здравоохранения', 'https://www.who.int/ru/emergencies/diseases/novel-coronavirus-2019', ['target'=>'_blank']) ?>
            </li>
        </ul>

        <?= Html::img('/sars/images/hotline.jpg', ['class'=>'img-center-responsive']) ?>

        <?php if ($model1) { ?>
            <div id="accordion" style="margin: 30px 0;">
                <?php foreach ($model1 as $i=>$value) { ?>
                    <div class="accordion-item">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $i ?>" class="collapsed">
                            <h4><?= $value->name ?></h4>
                        </a>
                        <div id="collapse<?= $i ?>" class="panel-collapse collapse" aria-expanded="false">
                            <?php $childs = $value->districts;
                            if ($childs) { foreach ($childs as $j=>$child) { ?>
                                <div class="accordion-item-child">
                                    <a data-toggle="collapse" data-parent="#collapse<?= $i?>" href="#collapse<?= $i . $j ?>" class="collapsed">
                                        <h4><?= $child->name ?></h4>
                                    </a>
                                    <div id="collapse<?= $i . $j ?>" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                                        <div class="accordion-item-child-desc">
                                            <?= $child->content ?>
                                        </div>
                                    </div>
                                </div>
                            <?php } } ?>
                        </div>
                    </div>
                <?php } ?>
            </div>
        <?php } ?>

        <?= Html::tag('h4', 'Ссылки на информационные источники о COVID-19', ['class'=>'text-center']) ?>
        <?php if ($values) { ?>
            <ul style="list-style-type: none; padding: 0;">
                <?php foreach ($values as $value) {
                    $isNew = ($value['created_at'] + 86400 * 3 > date('U')) ? ' - <span class="text-success">Новое!</span>' : null;
                    $name = implode(' - ', ["#{$value['id']}", $value['name'], date('d.m.Y г.', $value['created_at'])]);
                    $url = "https://universantal.com/library/read/{$value['id']}";
                    echo Html::tag('li', Html::a('<i class="fa fa-book"></i> ' . $name . $isNew, $url, ['target'=>'_blank']), ['style'=>'margin-bottom: 5px;']);
                } ?>
            </ul>
            <?php
            echo LinkPager::widget([
                'pagination'=>$pagination
            ]);
        } ?>
    </div>
</div>

<?php
$this->registerCss('
.accordion-item {
    background-color: aliceblue;
    border: 1px solid #eee;
    border-left: 5px solid #193e85;
    margin-bottom: 10px;
    padding: 15px 30px 15px 15px;    
}
.accordion-item-child {
    background-color: #ffffff;
    border: 1px solid #ddd;
    padding: 15px 30px 15px 15px;
    margin-top: 10px;
}
.accordion-item-child-desc {
    padding: 15px 30px 15px 15px;
}
');