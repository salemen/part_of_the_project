<?php
use yii\helpers\Html;

$this->title = 'Справка';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div class="box-header with-border">
                <h3 class="box-title">Что такое наблюдение онлайн?</h3>
            </div>
            <div class="box-body">
                <strong><i class="fa fa-stethoscope margin-r-5"></i>Наблюдение онлайн</strong>
                <p class="text-muted">Это непрерывный мониторинг за состоянием здоровья по установленному протоколу наблюдения.</p>
                <hr>
                <strong><i class="fa fa-file-text-o margin-r-5"></i>Протокол наблюдения</strong>
                <p class="text-muted">Это перечень показателей которые необходимо заполнять самостоятельно с установленной регулярностью.</p>
                <hr>
                <strong><i class="fa fa-cogs margin-r-5"></i>Автоматизация</strong>
                <p class="text-muted">Система автоматически отслеживает данные и выдает рекомендации в зависимости от показателей и их комбинации.</p>
                <p class="text-muted">Если показатели выходят за критические уровни система сразу сигнализирует об этом центру мониторинга.</p>
            </div>  
            <div class="box-header with-border">
                <h3 class="box-title">В чем полезность от наблюдения онлайн?</h3>
            </div>
            <div class="box-body">
                <p class="text-muted">Состояние вашего здоровья находится под постоянным мониторингом, контролем.</p>
                <p class="text-muted">В случае отклонения показателей от заданной нормы – решение принимается быстрее. Медицинский работник свяжется с вами в активном режиме.</p>
                <p class="text-muted">Вы имеете легкий и доступный способ информировать своего врача о вашем состоянии здоровья.</p>
                <p class="text-muted">Вы формируете историю наблюдения. Динамика изменений показателей – один из важных критериев для принятия правильных решений.</p>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Кому нужно наблюдение онлайн?</h3>
            </div>
            <div class="box-body">
                <p class="text-muted">Людям, находящимся на карантине по разным причинам: эпидпоказания, назначения врача, самоизоляция.</p>
                <p class="text-muted">Мониторинг состояния течения заболевания при лечении в режиме "стационар на дому".</p>
                <p class="text-muted">Диспансерное наблюдение при хронических неинфекционных заболевания: сахарный диабет, гипертоническая болезнь, бронхиальная астма.</p>
                <p class="text-muted">Мониторинг результатов при проведении реабилитации на дому.</p>
                <p class="text-muted">Наблюдение за режимом выполнения предписаний врача (приема лекарств).</p>
            </div> 
            <div class="box-header with-border">
                <h3 class="box-title">Как встать под наблюдение онлайн?</h3>
            </div>
            <div class="box-body">
                <p class="text-muted">Заходите на наш сайт <?= Html::a('santal-online.ru', '/') ?> или <?= Html::a('0323.ru', '/') ?> с любого устройства способного поддерживать связь.</p>
                <p class="text-muted">Главное меню -> Медсервисы -> <?= Html::a('Наблюдение онлайн', ['/monitor'],  ['class'=>(Yii::$app->user->isGuest) ? 'btn-login' : null]) ?></p>
                <p class="text-muted">Далее следуете по инструкции.</p>
            </div>
            <div class="box-header with-border">
                <h3 class="box-title">Как выйти из "наблюдение онлайн"?</h3>
            </div>
            <div class="box-body">
                <p class="text-muted">При окончании срока наблюдения, установленного врачом, система сама оповестит вас об окончании сроков наблюдения.</p>
                <p class="text-muted">Если Вы хотите самостоятельно прекратить наблюдение – нажмите на кнопку "снять наблюдение".</p>
            </div> 
        </div>        
    </div>
</div>