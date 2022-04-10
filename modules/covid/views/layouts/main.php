<?php
use yii\helpers\Html;
use app\assets\AppAsset;
use app\assets\AdminLTEBowerAsset;
use app\assets\JqueryEasyzoomAsset;
use app\widgets\Alert;

AppAsset::register($this);
AdminLTEBowerAsset::register($this);
JqueryEasyzoomAsset::register($this);
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <?= (constant('YII_DEBUG')) ? '' : $this->render('@app/views/layouts/_part/scripts') ?>
        <title><?= Html::encode($this->title . ' | САНТАЛЬ Онлайн-Поликлиника') ?></title>
        <?php $this->head() ?>
    </head>
    <body class="fixed sidebar-mini" style="font-size: 18px !important;">
        <?php $this->beginBody() ?>
            <div id="preloader">
                <div class="preloader-window"></div>
                <div class="preloader-content"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="color: #193e85;"></i></div>        
            </div>
            <div class="wrapper">
                <?= $this->render('_part/header') ?>
                <?= $this->render('_part/sidebar') ?>
                <div class="content-wrapper" style="background-color: #ffffff;">
                    <section class="content">
                        <?= Html::tag('h2', $this->title, ['class'=>'text-center text-primary', 'style'=>'margin-bottom: 50px; margin-top: 10px;']) ?>
                        <?= Html::tag('div', $this->render('_part/description') . $content, ['style'=>'padding: 0 15px;']) ?>
                        <?= Alert::widget() ?>
                    </section>
                </div>
                <?= $this->render('_part/modal') ?> 
                <?= Html::a('<i class="fa fa-chevron-up"></i>', '#', ['class'=>'btn-scrolltop']) ?>
            </div>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>