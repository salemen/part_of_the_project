<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Breadcrumbs;
use app\assets\AppAsset;
use app\widgets\Alert;

AppAsset::register($this);
?>


<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <?= Html::csrfMetaTags() ?>
    <?= (constant('YII_DEBUG') === false) ? $this->render('_part/scripts') : null ?>
    <title><?= Html::encode($this->title . ' | САНТАЛЬ Онлайн-Поликлиника') ?></title>
    <?php $this->head() ?>



<meta name="google-site-verification" content="jSnWUVjob9DwaldDXgDDFAMNX6C_rU6uptE02KEs_EE" />
<meta name='wmail-verification' content='003d48771bbd093bbe0b8b8f4c70efce' />
<meta name="yandex-verification" content="48122e50f3f00594" />
<meta name="yandex-verification" content="dd069aa3dbacb491" />
<script async src="https://code.jivosite.com/widget/TiZYXz70bG"></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');
  ga('create', 'UA-90760702-24', 'auto');
  ga('send', 'pageview');
</script>
<script>
    (function (w, d, c) {
    (w[c] = w[c] || []).push(function() {
        var options = {
            project: 4499535
        };
        try {
            w.top100Counter = new top100(options);
        } catch(e) { }
    });
    var n = d.getElementsByTagName("script")[0],
    s = d.createElement("script"),
    f = function () { n.parentNode.insertBefore(s, n); };
    s.type = "text/javascript";
    s.async = true;
    s.src =
    (d.location.protocol == "https:" ? "https:" : "http:") +
    "//st.top100.ru/top100/top100.js";

    if (w.opera == "[object Opera]") {
    d.addEventListener("DOMContentLoaded", f, false);
} else { f(); }
})(window, document, "_top100q");
</script>
<script type="text/javascript" >
    (function (d, w, c) {
        (w[c] = w[c] || []).push(function() {
            try {
                w.yaCounter45355230 = new Ya.Metrika({
                    id:45355230,
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    webvisor:true
                });
            } catch(e) { }
        });

        var n = d.getElementsByTagName("script")[0],
            s = d.createElement("script"),
            f = function () { n.parentNode.insertBefore(s, n); };
        s.type = "text/javascript";
        s.async = true;
        s.src = "https://mc.yandex.ru/metrika/watch.js";

        if (w.opera == "[object Opera]") {
            d.addEventListener("DOMContentLoaded", f, false);
        } else { f(); }
    })(document, window, "yandex_metrika_callbacks");
</script>
<!--<script type="text/javascript" async="" src="//code.jivosite.com/script/widget/BK4bzAUxRm"></script>-->
<noscript>
    <img src="//counter.rambler.ru/top100.cnt?pid=4499535" alt="Топ-100" />
</noscript>
<noscript>
    <img src="https://mc.yandex.ru/watch/45355230" style="position:absolute; left:-9999px;" alt="" />
</noscript>

<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.2/css/all.css" integrity="sha384-fnmOCqbTlWIlj8LyTjo7mOUStjsKC4pOpQbqyi7RrhN7udi9RwhKkMHpvLbHG9Sr" crossorigin="anonymous">

</head>
<body class="layout-top-nav skin-black">
<?php $this->beginBody() ?>

<div id="preloader">
    <div class="preloader-window"></div>
    <div class="preloader-content"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="color: #193e85;"></i></div>        
</div>
<div class="wrapper">

    <?= $this->render('_part/header') ?> 
    
    <div class="content-wrapper">

        <?php if (isset($this->params['wide-page'])) { ?>
            <div class="container-wide">

                <?= $content ?>
            </div>
        <?php } else { ?>
            <div class="container">
                <section class="content-header">

                    <?php $to =  Url::to();

                    if ($to == "/doctor-special"){ ?>
                    <div id="showHideContent">
                        <div class="logotip">
                            <?= Html::img('@web/img/FotoHeader1.jpg', ['alt' => 'Логотип']) ?>
                            <a href = '#vniz' class="kn btn btn-primary" style="padding: 15px 40px;">Записаться на консультацию</a>
                            <h1 class="cons1">ОНЛАЙН КОНСУЛЬТАЦИИ ПО COVID-19</br>
                             И ПОСТКОВИДНОМУ СИНДРОМУ</h1>
                            <h2 class="cons3">СВЯЗЬ С ВРАЧЕМ ДО 7 ДНЕЙ</h2>
                        </div>
                        <div class="logotip-min">

                            <?= Html::img('@web/img/FotoHeader2.jpg', ['alt' => 'Логотип']) ?>
                            <h2 class="cons4">СВЯЗЬ С ВРАЧЕМ ДО 7 ДНЕЙ</h2>
                            <a href = '#vniz' class="kn-min btn btn-primary" >Записаться на консультацию</a>
                        </div>
                    </div> <?php } else {?>


                    <?php  } ?>

                    <?= Breadcrumbs::widget(['links'=>isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
                </section>
                <section class="content" id="vniz" style="padding-top: 65px;">
                    <div id="content-desc" style="display: none;">  <?= $this->render('_part/description') ?></div>
                    <?= $content ?>
                </section>
            </div>
        <?php } ?>
        <?= Alert::widget() ?>
    </div>
    
    <?= $this->render('_part/modal') ?> 
    <?= isset($this->params['hide-footer']) ? null : $this->render('_part/footer') ?>
    <?= $this->render('_part/mega-menu') ?>
    
    <?= Html::a('<i class="fa fa-chevron-up"></i>', '#', ['class'=>'btn-scrolltop']) ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

<script>
    // Найти все ссылки начинающиеся на #
    const anchors = document.querySelectorAll('a[href^="#"]')

    // Цикл по всем ссылкам
    for(let anchor of anchors) {
        anchor.addEventListener("click", function(e) {
            e.preventDefault() // Предотвратить стандартное поведение ссылок
            // Атрибут href у ссылки, если его нет то перейти к body (наверх не плавно)
            const goto = anchor.hasAttribute('href') ? anchor.getAttribute('href') : 'body'
            // Плавная прокрутка до элемента с id = href у ссылки
            document.querySelector(goto).scrollIntoView({
                behavior: "smooth",
                block: "start"
            })
        })
    }
</script>
