<?php
use yii\helpers\Html;

if ($model) { ?>    
    <div id="carousel-generic" class="carousel slide hidden-xs" data-ride="carousel">
        <div class="carousel-inner">
            <?php foreach ($model as $key=>$val) {
                echo Html::beginTag('div', ['class'=>(($key == 0) ? 'item active' : 'item')]);
                echo ($val->url_href) ? Html::a(Html::img('/uploads/' . $val->file), $val->url_href) : Html::img('/uploads/' . $val->file);
                echo Html::endTag('div');
            } ?>
        </div>
        <?php if (count($model) > 1) { 
            echo Html::a('<span class="fa fa-angle-left"></span>', '#carousel-generic', ['class'=>'left carousel-control', 'data-slide'=>'prev']);
            echo Html::a('<span class="fa fa-angle-right"></span>', '#carousel-generic', ['class'=>'right carousel-control', 'data-slide'=>'next']);
        } ?>
    </div>
<?php } else { ?>
    <div class="bg-parallax hidden-xs" style="background-image: url(/img/banner.jpg)">
        <div class="container">
            <div class="row">
                <div class="col-md-6 inverse" style="background-color: rgba(0, 0, 0, 0.5); border-radius: 10px; padding: 40px;">
                    <h3 style="font-size: 40px; font-weight: 600; text-transform: uppercase;">Онлайн-Поликлиника</h3>
                    <p style="font-size: 20px; margin-bottom: 40px;">Врачебные онлайн-консультации<br> без регистратуры и очередей</p>
                    <?= Html::a('Получить консультацию <br class="hidden-sm hidden-md hidden-xl hidden-lg" />специалиста', ['/doctor'], ['class'=>'btn btn-xlg btn-danger btn-block ' . ((Yii::$app->user->isGuest) ? 'btn-login' : null), 'style'=>'font-size: 18px; padding: 16px 12px;'])?>
                </div>
            </div>
        </div>
    </div>
<?php } ?>

<?php
$this->registerCss('
.carousel-inner > .item {
    margin: 0;
}
');