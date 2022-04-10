<?php
use yii\helpers\Html;

$this->title = 'Страница находится на стадии разработки';
?>

<div style="margin: 25% auto; text-align: center; width: 360px;">
    <h2 class="headline text-info"><i class="fa fa-5x fa-cog fa-spin text-yellow"></i></h2>
    <h3><?= $this->title ?></h3>
    <p style="font-size: 16px;">Пожалуйста, зайдите позже</p>
    <br>
    <?= Html::a('Вернуться', Yii::$app->homeUrl, ['class'=>'btn btn-primary btn-block btn-flat']) ?>
</div>