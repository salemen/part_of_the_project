<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CommonUser;
use app\models\anketa\AnketaSession;
use app\models\consult\Consult;
use app\models\employee\Employee;
use app\models\user\UserDocs;

$this->title = 'Инструкции для врача';
$this->params['breadcrumbs'][] = $this->title;
$user_id = Yii::$app->user->id;

$usernameHeader = Yii::$app->session->has('employee_santal') ? 'Логин' : 'Инд. номер';
?>

<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="col-md-9 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="row"><br><br>
            <div> Уважаемый(ая) <?= $model->fullname ?><br>
        В данном разделе Вы найдете для себя полезную информацию по работе в личном кабинете, а именно: как редактировать свои личные данные, как консультировать онлайн, как рассчитывается доход от ваших консультаций, как получить подробную информацию по возникающим вопросам.<br>
        Ознакомиться с инструкцией по проведению онлайн-консультации можно в текстовом документе <a href='/docs/online-consult.pdf'><u>Онлайн консультация на сайте 0323.ru Инструкция для врача</u></a> или в видео-инструкции<br><br>
            </div>          
        </div>
    </div>
    <div class="row">
        <div class="col-md-6 col-xs-12 col-md-offset-2">
        <u><h4><?= Html::a('Онлайн консультация на сайте 0323.ru Инструкция для врача', ['/docs/online-consult.pdf']) ?></h4></u><br><br>
            <div>
                <b><h4>0323.ru - платформа для врачей, клиник и пациентов</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/anBTXxlkBzQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div><br><br><br>
        </div>
        <div class="col-md-6 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Онлайн консультация</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/M8SMwTmARrw" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div><br><br>
        </div>
        <div class="col-md-6 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Как консультировать через 0323.ru ?</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/WJH0RpGlzr4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br><br>
            </div>
        </div>
        <div class="col-md-9 col-sm-12 col-xs-12 col-md-offset-1">
            <div><br><br>
                Остались вопросы, напишите пожалуйста менеджеру проекта онлайн-поликлиники <a href="/"><u>0323.ru</u></a> Шитиковой Ольге santal-online@0370.ru или +7 913 865-03-69 (WhatsApp)
                
                <br><br>
            </div>
        </div>
    </div>
</div>