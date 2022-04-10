<?php
use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\CommonUser;
use app\models\anketa\AnketaSession;
use app\models\consult\Consult;
use app\models\employee\Employee;
use app\models\user\UserDocs;

$this->title = 'Инструкции для пациента';
$this->params['breadcrumbs'][] = $this->title;

?>


    <div class="col-md-10 col-sm-12 col-xs-12 col-md-offset-1">
        <div class="row"><br><br>
            <center><div>
                В данном разделе Вы найдете для себя полезную информацию по работе с онлайн поликлиникой 0323.ru<br>
                Ознакомиться с инструкцией можно в текстовом документе <a href='/docs/online_consult_pacient.pdf'><u>Онлайн консультация на сайте 0323.ru Инструкция для пациентов</u></a> или в видео-инструкции<br><br>
                </div></center>
        </div>
    </div>
    <div class="row">
        <center class="col-md-8 col-xs-12 col-md-offset-2">
           <center> <h4><?= Html::a('Онлайн консультация на сайте 0323.ru Инструкция для пациента', ['/docs/online_consult_pacient.pdf']) ?></h4></center><br><br>
            <div>
                <b><h4>Вызов врача</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/V-3HuQ94Kv8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div><br><br><br>
        </div>
        <div class="col-md-8 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Запись на прием</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/lnqOCwPbpxk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div><br><br>
        </div>
        <div class="col-md-8 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Онлайн консультация</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/ZnHzht2diYQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br><br>
            </div>
        </div>
        <div class="col-md-8 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Расшифровка</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/u9qDa104urM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br><br>
            </div>
        </div>
        <div class="col-md-8 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Ситуационный центр</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/JO3kNoby-d8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br><br>
            </div>
        </div>
        <div class="col-md-8 col-xs-12 col-md-offset-2">
            <div>
                <b><h4>Самодиагностика</h4></b><br><br>
                <iframe class="video-consult" width="100%" height="315px" src="https://www.youtube.com/embed/DypaGLhjN2E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                <br><br>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12 col-md-offset-1">
            <div><br><br>
                Остались вопросы, напишите пожалуйста менеджеру проекта онлайн-поликлиники <a href="/"><u>0323.ru</u></a> Шитиковой Ольге santal-online@0370.ru или +7 913 865-03-69 (WhatsApp)

                <br><br>
            </div>
        </div>
    </div>
