<?php
use yii\helpers\Html;
?>
<meta http-equiv="Cache-control" content="no-cache">
<footer class="main-footer">
    <!-- HoverSignal -->

    <script type="text/javascript" >
        (function (d, w) {
            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://app.hoversignal.com/Api/Script/807c4600-c4fd-41af-8be1-b9ff8e0bc8e2";
            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window);
    </script>
    
    <!-- /Hoversignal -->
    <div class="hidden-xs" style="font-size: 14px; padding: 20px 0 30px;">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <h4 style="font-size: 14px;">Пациенту</h4>
                    

                        <li><?= Html::a('Консультации', ['/doctor']) ?></li>
                        <li><?= Html::a('Консультация по COVID', ['/doctor-special']) ?></li>
                        <li><?= Html::a('Информация по COVID', ['/covid']) ?></li>
                        <li><?= Html::a('Соглашение', ['/about/info']) ?></li>
                        <li><?= Html::a('Информация', ['/about']) ?></li>
                        <li><?= Html::a('Лицензии', ['/about/license']) ?></li>
                        <li><?= Html::a('Контакты', ['/about/contact']) ?></li>
                        <li><?= Html::a('Безопасность платежей', ['/about/paysecure']) ?></li>
                    
                </div>
                <div class="col-md-3">
                    <h4 style="font-size: 14px;">Сервисы</h4>

                        <li><?= Html::a('Прикрепление к поликлинике', 'http://oms.0370.ru', ['target'=>'_blank']) ?></li>
                        <li><?= Html::a('Наблюдение онлайн', ['/monitor']) ?></li>
                        <li><?= Html::a('Запись на госпитализацию', 'https://hospital.0370.ru', ['target'=>'_blank']) ?></li>
                        <li><?= Html::a('Результаты анализов', ['/research']) ?></li>
                       <li><?= Html::a('Результаты теста на COVID', ['/covid/diagnosis']) ?></li>
                        <li><?= Html::a('Расшифровка анализов', ['/interp']) ?></li>
                        <li><?= Html::a('Расшифровка ЭКГ Онлайн', ['/cardio']) ?></li>
                        <li><?= Html::a('Самодиагностика', ['/symptom']) ?></li>
                       <li><?= Html::a('Онлайн платежи', ['/pay']) ?></li>
                    
                </div>
                <div class="col-md-3">
                    <h4 style="font-size: 14px;">Самодиагностика</h4>

                    <li><?= Html::a('По признакам болезни',['/symptom']) ?></li>
                    <li><?= Html::a('Оценка слуха',  ['/express-test/hearing']) ?></li>
                    <li><?= Html::a('Оценка зрения',  ['/express-test/vision']) ?></li>

                    <li><?= Html::a('Расчет факторов риска', ['/anketa']) ?></li>
                    <li><?= Html::a('Тест HADS', ['/test/index?test_id=2']) ?></li>
                    <li><?= Html::a('Тест Цунга', ['/test/index?test_id=1']) ?></li>
                    <li><?= Html::a('Экспресс тесты', ['/express-test']) ?></li>
                    <li><?= Html::a('Опросник здоровья', ['/']) ?></li>
                    <li><?= Html::a('Датчик осанки iBack', ['/iback']) ?></li>
                    
                </div>
                <div class="col-md-3">
                    <?= Html::img('/img/logo/logo-mobile.jpg', ['class'=>'img-responsive']) ?>
                    <br>
                    <p>Звоните нам: <?= Html::a('+7 913 865-03-69', 'tel:+79138650369') ?></p>
                    <p>Пишите нам: <?= Html::a('santal-online@0370.ru', 'mailto:' . Yii::$app->params['mainEmail'], ['class'=>'mail-link']) ?></p>              
                    
<p>Мы в соцсетях:		
&nbsp;		
            <A title="Facebook" style="color:#000" HREF="https://www.facebook.com/santalonline-101437868223350" target=_blank><i
                        class="fab fa-facebook-square fa-lg"></i></A>
            &nbsp;&nbsp;
            <A title="Instagram" style="color:#000" HREF="https://www.instagram.com/santalonline/" target=_blank><i
                        class="fab fa-instagram fa-lg"></i></A>
            &nbsp;&nbsp;
			<A title="YouTube" style="color:#000" HREF="https://www.youtube.com/playlist?list=PLzvTdg03yxF2_0IocWDt6bOF3VjTGTWeH" target=_blank><i
                        class="fab fa-youtube fa-lg"></i></A>
			&nbsp;&nbsp;
			<A title="Яндекс-Дзен" style="color:#000" HREF="https://zen.yandex.ru/id/6135d67850904d49d6630fdc" target=_blank><IMG style="margin-top: -3px; width:18px" SRC="https://universantal.com/img/icon_zen.png"></A>
</div>
					
					
					
					
                </div>
            </div>        
        </div>
    </div>   
    <div class="container">        
        <div class="row" style="align-items: center; display: flex;">
            <div class="col-xs-12 col-md-12">
                <span style="font-size: 80%;">&copy; САНТАЛЬ Онлайн-Поликлиника 2017 - <?= date('Y') ?>. Онлайн консультации носят только информационный и рекомендательный характер, не являются диагнозом и не заменяют очной консультации.</span>
				<BR><BR>
            </div>
        </div>          
    </div>
</footer> 