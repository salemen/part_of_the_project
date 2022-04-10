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
    <div class="boxform">
        <div class="login-logo">
            <b><?= Yii::$app->name ?></b>
        </div>
        <div class="login-box-body">
            <?= Html::tag('div', Html::img('/img/logo/logo-mobile.jpg', ['img-responsive']), ['class'=>'text-center']) ?>
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