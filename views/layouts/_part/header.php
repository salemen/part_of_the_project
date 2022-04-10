<?php
use yii\helpers\Html;
use app\data\MainMenu;
use app\widgets\Menu;

$mainMenu = new MainMenu();
$mainMenu->visibility = 'header';
$items = $mainMenu->getItems();
?>

<header class="main-header">
    <nav class="navbar navbar-static-top navbar-fixed-top">
        <div class="container adress-panel" >

            <div class="logotip" style="margin-right:75px; margin-top: 5px; margin-bottom: 5px; float:right">
                <span style="margin-right:20px; color:#fff5e7; font-size: 19px">Задать вопрос дежурному врачу&nbsp;&nbsp;
                    <a style="color:#fff5e7; font-size: 18px" href="tel:8-913-865-03-69">8-913-865-03-69</a></span>

                <img style="width:26px; margin-bottom: 5px;" src="/img/whatsapp_icon.png">
                <?= Html::a('WhatsApp', ' https://api.whatsapp.com/send?phone=+79138650369',['style'=> 'color:#fff5e7!important;']) ?>

            </div>
            <center><div class="logotip-min" style=" margin-top: 5px; margin-bottom: 5px;">
                    <center> <span style="margin-right:20px; color:#fff5e7; font-size: 15px">Вопрос дежурному врачу
                       <a style="color:#fff5e7; font-size: 17px" href="tel:8-913-865-03-69">8913-865-03-69</a></span></center>

                    <img style="width:26px; margin-bottom: 5px;" src="/img/whatsapp_icon.png">
                    <?= Html::a('WhatsApp', ' https://api.whatsapp.com/send?phone=+79138650369',['style'=> 'color:#fff5e7!important;']) ?>

                </div> </center>
        </div>
        <div class="container">
<!--            <div style="float: left; margin-right: 10px;">-->
<!--                = Html::a('<i class="fa fa-bars"></i>', '#', ['data-target'=>'#mega-menu', 'id'=>'mega-menu-toggle'])
           </div>-->
            <div class="navbar-header">
                <?= Html::a(Html::img('/img/logo/logo-wide2.jpg', ['class'=>'img-responsive header-logo hidden-xs hidden-sm hidden-md']) . Html::img('/img/logo/logo-mobile.jpg', ['class'=>'img-responsive header-logo-mobile hidden-lg']), '/', ['class'=>'navbar-brand']) ?>

                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse" aria-expanded="false">
                    <i class="fa fa-ellipsis-v" style="font-size: 20px;"></i>
                </button>
            </div>                 
            <div class="collapse navbar-collapse pull-right" id="navbar-collapse">
                <?= Menu::widget([
                    'activateParents'=>false,
                    'defaultIconHtml'=>'', 
                    'encodeLabels'=>false,
                    'items'=>$items,
                    'options'=>['class'=>'nav navbar-nav'],
                    'submenuTemplate'=>"\n<ul class='dropdown-menu dropdown-menu-right' {show}>\n{items}\n</ul>\n",
                    'topNav'=>true    
                ]) ?>
            </div>   
        </div>
    </nav>
</header>