<?php
use yii\helpers\Html;

$this->title = 'Нормативно-правовые документы';
$this->params['breadcrumbs'][] = ['label'=>'Информация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div>
            <p>
                <?= Html::a('1. Приказ Минздрава России "Об утверждении порядка организации и оказания медицинской помощи с применением телемедицинских технологий"', '/docs/prikaz_minzdrav.pdf', ['target'=>'_blank'])?>
            </p>   
        </div>
    </div>
</div>    