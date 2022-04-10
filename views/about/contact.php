<?php
use yii\helpers\Html;

$this->title = 'Контакты';
$this->params['breadcrumbs'][] = ['label'=>'Информация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <h4>Звоните нам:</h4>
        <div style="margin-left: 10px; ">
            <p><i class="fa fa-phone"></i> <?= Html::a('+7 913 865-03-69', 'tel:+79138650369') ?></p>
        </div>
        <br>

        <h4>Пишите нам:</h4>
        <div style="margin-left: 10px;">
            <p><i class="fa fa-at"></i> <?= Html::a('santal-online@0370.ru', 'mailto:' . Yii::$app->params['mainEmail'] . '?subject=Вопрос от посетителя сайта "Онлайн-Поликлиника"') ?></p>
            <p><i class="fa fa-at"></i> <?= Html::a('shigo@0370.ru', 'mailto:' . Yii::$app->params['managerEmail'] . '?subject=Вопрос от посетителя сайта "Онлайн-Поликлиника"') ?></p>
            <p><i class="fa fa-at"></i> <?= Html::a('shmb@0370.ru', 'mailto:' . Yii::$app->params['adminEmail'] . '?subject=Вопрос от посетителя сайта "Онлайн-Поликлиника"') ?></p>
        </div> 
        <br>

        <h4>Мы в соцсетях:</h4>
        <div style="margin-left: 10px;">
            <p><i class="fa fa-camera-retro" aria-hidden="true"></i> <?= Html::a('@santalonline', 'https://www.instagram.com/santalonline/', ['target'=>'_blank']) ?></p>
        </div>
        <br>

        <h4>ООО "ЦСМ"</h4>
        <div style="margin-left: 10px;">
            <p><span>Адрес юридический: </span>634059, г. Томск, ул. Смирнова, д. 30</p>
            <p><span>Директор: </span>Рабцун Евгений Анатольевич</p>
            <p><span>ИНН / КПП: </span>7017135954 / 701701001</p>
            <p><span>ОГРН / ОКПО: </span>1067017007188 / 79197187</p>
        </div>
    </div>
</div>    