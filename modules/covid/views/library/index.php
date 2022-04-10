<?php
use yii\helpers\Html;
use yii\widgets\LinkPager;

$this->title = 'Библиотека COVID-19';
?>

<?= Html::tag('h4', 'Ссылки на информационные источники о COVID-19', ['class'=>'text-center']) ?>

<div class="row" style="margin-top: 2%;">
    <div class="col-md-12">
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