<?php
use yii\helpers\Html;

$this->title = $name;
$code = Yii::$app->response->getStatusCode();
?>

<div style="margin: 25% auto; text-align: center; width: 360px;">
    <h2 class="headline text-info"><i class="fa fa-5x fa-warning text-yellow"></i></h2>
    <h3><?= $name ?></h3>
    <p><?= nl2br(Html::encode($message)) ?></p>
    <br>
    <?= Html::a('Вернуться', ($code == 403) ? Yii::$app->request->referrer : Yii::$app->homeUrl, ['class'=>'btn btn-primary btn-block btn-flat', 'name'=>'login-button']) ?>
</div>