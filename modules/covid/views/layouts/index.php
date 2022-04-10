<?php
use yii\helpers\Html;
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
        <?= Html::csrfMetaTags() ?>
        <?= (constant('YII_DEBUG')) ? '' : $this->render('@app/views/layouts/_part/scripts') ?>
        <title><?= Html::encode($this->title . ' | САНТАЛЬ Онлайн-Поликлиника') ?></title>
        <?php $this->head() ?>
    </head>
    <body class="layout-top-nav skin-black">
    <?php $this->beginBody() ?>
    <div id="preloader">
        <div class="preloader-window"></div>
        <div class="preloader-content"><i class="fa fa-spinner fa-pulse fa-3x fa-fw" style="color: #193e85;"></i></div>        
    </div>
    <div class="wrapper">  
        <?= $this->render('../../../../views/layouts/_part/header') ?>   
        <div class="content-wrapper" style="align-items: center; background-color: #ffffff !important; display: flex; flex-wrap: wrap; justify-content: center; padding-top: 80px;">
            <div class="container" style="padding-top: 21px;>
                <?= $content ?>
            </div>
            <?= Alert::widget() ?>
        </div>

        <?= $this->render('../../../../views/layouts/_part/modal') ?>
        <?= isset($this->params['hide-footer']) ? null : $this->render('../../../../views/layouts/_part/footer') ?>
        <?= $this->render('../../../../views/layouts/_part/mega-menu') ?>

        <?= Html::a('<i class="fa fa-chevron-up"></i>', '#', ['class'=>'btn-scrolltop']) ?>
    </div>
    <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>