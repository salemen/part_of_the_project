<?php
use yii\helpers\Html;
use app\assets\AppAsset;

AppAsset::register($this);

$module = Yii::$app->controller->module;

$bodyClass = $module->bodyClass ? : 'skin-blue';
$sidebarCollapse = (Yii::$app->session->has('sidebar-collpsed')) ? 'sidebar-collapse' : null;
?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode('Онлайн-Поликлиника | Панель управления') ?></title>
    <?php $this->head() ?>
</head>
<body class="fixed sidebar-mini <?= implode(' ', [$bodyClass, $sidebarCollapse]) ?>">
<?php $this->beginBody() ?>
<div class="wrapper">
<?= $this->render('_part/header') ?>
<?= $this->render('/layouts/sidebar') ?>
<?= $this->render((isset($this->params['dashboard'])) ? '_part/dashboard' : '_part/content' , ['bodyClass'=>$bodyClass, 'content'=>$content]) ?>
<?= $this->render('_part/modal') ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>