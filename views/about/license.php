<?php
use yii\helpers\Html;

$this->title = 'Нормативно-правовые документы';
$this->params['breadcrumbs'][] = ['label'=>'Информация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <p>Наша лицензия:</p>
        <ul>
            <li><?= Html::a('ООО «ЦСМ»', '/docs/lic/csm.pdf', ['target'=>'_blank']) ?></li>
        </ul>
        <hr>
        <p>Лицензии наших партнеров:</p>
        <ul>
            <li><?= Html::a('ООО «Центр Семейной Медицины»', '/docs/lic/csm2.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «ЦРТ «Аист»', '/docs/lic/aist.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «ЦСМ-стоматология»', '/docs/lic/stom.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «МЕДХЭЛП»', '/docs/lic/medhelp.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «Институт мужского здоровья»', '/docs/lic/imz.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «ЦКБ»', '/docs/lic/ckb.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «САНТАЛЬ»', '/docs/lic/nsk.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «САНТАЛЬ 123»', '/docs/lic/glnk.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «САНТАЛЬ 17»', '/docs/lic/kizil.pdf', ['target'=>'_blank']) ?></li>
            <li><?= Html::a('ООО «САНТАЛЬ 23»', '/docs/lic/krd.pdf', ['target'=>'_blank']) ?></li>
        </ul> 
    </div>
</div>    