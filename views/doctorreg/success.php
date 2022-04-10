<?php
use yii\helpers\Html;
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
    
	<meta name="robots" content="noindex, nofollow">
	
	<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <?= Html::csrfMetaTags() ?>
    <?= (constant('YII_DEBUG') === false) ? $this->render('/layouts/_part/scripts') : null ?>
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
    <?= $this->render('/layouts/_part/header') ?> 
    
    <div class="content-wrapper">
        <?php if (isset($this->params['wide-page'])) { ?>
            <div class="container-wide">
                          </div>
        <?php } else { ?>
            <div class="container" style="width: 1270px;">
                <section class="content-header">
                    <div id="showHideContent">
                        <h1 style="font-size: 22px;"><?= isset($this->params['custom-title']) ? $this->params['custom-title'] : $this->title ?></h1>
                    </div >
                    <?= Breadcrumbs::widget(['links'=>isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : []]) ?>
                </section>
                <section class="content">
                    <div id="content-desc" style="display: none;">  <?= $this->render('/layouts/_part/description') ?></div>
                    <?= $content ?>
                </section>
            </div>
        <?php } ?>
        <?= Alert::widget() ?>
    </div>
    
    <?= $this->render('_part/modal') ?> 
    <?= isset($this->params['hide-footer']) ? null : $this->render('/_part/footer') ?>
    <?= $this->render('_part/mega-menu') ?>
    
    <?= Html::a('<i class="fa fa-chevron-up"></i>', '#', ['class'=>'btn-scrolltop']) ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>