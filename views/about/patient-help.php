<?php
use yii\helpers\Html;
use app\widgets\Menu;

$this->title = 'Инструкции для пациента';
$this->params['breadcrumbs'][] = ['label'=>'Информация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;


?>
<div class="row">
<?= Html::a('<i class="fa fa-chevron-right"></i>', '#', ['class'=>'btn-aside-toggle']) ?>
<div class="aside-column col-md-3">
    <div id="affixBlock">
        <?= Html::a('<i class="fa fa-remove"></i>', '#', ['class'=>'btn-aside-toggle-mobile']) ?>
        <div class="box box-body box-primary">
            <?= Menu::widget([
                'defaultIconHtml'=>null,
                'options'=>['class'=>'list-group list-group-unbordered'],
                'items'=>[
                    ['label'=>'О сервисе', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/service']],
                    ['label'=>'Контакты', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/contact']],
                    ['label'=>'Нормативно-правовые документы', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/documents']],
                    ['label'=>'Лицензии', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/license']],
                    ['label'=>'Вакансии', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/vacancy']],
                    ['label'=>'Часто задаваемые вопросы', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/questions']],
                    ['label'=>'Соглашения', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/info']],
                    ['label'=>'Инструкция', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/patient-help']],
                    ['label'=>'Безопасность платежей', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/paysecure']],
                ]
            ]) ?>
        </div>
    </div>
</div>

 <div class="col-md-9">
   <center> <div class="row"><br><br>
        <center><div>
                В данном разделе Вы найдете для себя полезную информацию по работе с онлайн поликлиникой 0323.ru<br>
                Ознакомиться с инструкцией можно в текстовом документе <a href='/docs/online_consult_pacient.pdf'><u>Онлайн консультация на сайте 0323.ru Инструкция для пациентов</u></a> или в видео-инструкции<br><br>
            </div></center>
        <center> <h4><?= Html::a('Онлайн консультация на сайте 0323.ru Инструкция для пациента', ['/docs/online_consult_pacient.pdf']) ?></h4></center><br><br>
        <div>
            <b><h4>Вызов врача</h4></b><br><br>
            <iframe class="video-consult" width="80%" height="315px" src="https://www.youtube.com/embed/V-3HuQ94Kv8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
        </div><br><br><br>
     <div>
        <b><h4>Запись на прием</h4></b><br><br>
        <iframe class="video-consult" width="80%" height="315px" src="https://www.youtube.com/embed/lnqOCwPbpxk" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>

    </div> <br><br>

        <div>
            <b><h4>Онлайн консультация</h4></b><br><br>
            <iframe class="video-consult" width="80%" height="315px" src="https://www.youtube.com/embed/ZnHzht2diYQ" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <br><br>
        </div>
        <div>
            <b><h4>Расшифровка</h4></b><br><br>
            <iframe class="video-consult" width="80%" height="315px" src="https://www.youtube.com/embed/u9qDa104urM" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <br><br>
        </div>
        <div>
            <b><h4>Ситуационный центр</h4></b><br><br>
            <iframe class="video-consult" width="80%" height="315px" src="https://www.youtube.com/embed/JO3kNoby-d8" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <br><br>
        </div>
        <div>
            <b><h4>Самодиагностика</h4></b><br><br>
            <iframe class="video-consult" width="80%" height="315px" src="https://www.youtube.com/embed/DypaGLhjN2E" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            <br><br>
        </div>

       <div><br><br>
           Остались вопросы, напишите пожалуйста менеджеру проекта онлайн-поликлиники <a href="/"><u>0323.ru</u></a> Шитиковой Ольге santal-online@0370.ru или +7 913 865-03-69 (WhatsApp)

           <br><br>
       </div>

       </div></center>

</div>
