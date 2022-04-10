<?php
use yii\helpers\Html;
use yii\helpers\Url;
use app\models\monitor\MonitorPassport;

$this->title = 'Наблюдение онлайн';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Мониторинг состояния здоровья при профилактике (карантине) и заболевании'
], 'description');

$user = Yii::$app->user;
?>

<div class="row">    
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div style="padding: 20px 10px;">
                <div class="row">
                    <div class="col-md-12">
                        <a href="<?= Url::to(['passport', 'type'=>MonitorPassport::TYPE_ORVI])?>" class="<?= ($user->isGuest) ? 'btn-login' : null ?>">
                            <div class="row">
                                <div class="col-md-3 col-md-offset-1">
                                    <?= Html::img('/img/monitor_1.png', ['class'=>'img-responsive'])?>
                                </div>
                                <div class="col-md-8" style="margin-top: 75px;">
                                    <b style="font-size: 20px;">Онлайн-наблюдение Ковид</b>
                                    <p style="margin-top: 10px;">
                                        Протокол мониторинга инфекционных заболеваний: COVID–19
                                    </p>
                                </div>
                            </div>
                        </a>   
                    </div>

                </div>
                <br>
                <?= Html::a('Что такое наблюдение онлайн?', ['/about/spravka']) ?>
            </div>
        </div>
    </div>
</div>