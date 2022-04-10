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
        <?= (constant('YII_DEBUG') === false) ? $this->render('_part/scripts') : null ?>
        <title><?= Html::encode(Yii::$app->name) ?></title>
        <?php $this->head() ?>
    </head>
    <?php $this->beginBody() ?>
    <body class="login-page">
    <div class="login-box" style="max-width: 485px;">
        <div class="login-box-body" style="padding-top: 30px;">
            <div class="logotip">
                <?= Html::tag('div', Html::img('/img/logo/logo-wide2.jpg', ['img-responsive']), ['class'=>'text-center','style'=>'font-weight: 300']) ?>
            </div>
            <div class="logotip-min">
                <?= Html::tag('div', Html::img('/img/logo/logo-mobile.jpg', ['img-responsive']), ['class'=>'text-center']) ?>
            </div>
            <br>
            <?= $content ?>
        </div>
        <br>
        <?= Html::a('Вернуться на сайт', '/', ['class'=>'btn btn-default btn-block btn-flat', 'style'=>'background-color: #FFFFFF;']) ?>
    </div>
    <?= Alert::widget() ?>
    </body>
    <?php $this->endBody() ?>
    </html>
<?php $this->endPage() ?>