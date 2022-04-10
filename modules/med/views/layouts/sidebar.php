<?php
use yii\helpers\Html;
use app\models\CommonUser;
use app\helpers\AppHelper;
use app\widgets\Menu;

$user = Yii::$app->user->identity;
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?= Html::img(CommonUser::getPhoto($user->id)) ?>
            </div>
            <div class="pull-left info">
                <p><?= AppHelper::shortFullname($user->fullname) ?></p>
                <span><i class="fa fa-circle text-success"></i> Online</span>
            </div>
        </div>
        <?= Menu::widget([
            'options'=>['class'=>'sidebar-menu tree', 'data-widget'=>'tree'],
            'items'=>[
                [
                    'label'=>'Главное меню',
                    'options'=>[
                        'class'=>'header'
                    ]
                ],
                [
                    'icon'=>'user-md',
                    'items'=>[
                        ['label'=>'Анкетирование', 'url'=>['/med/anketa']]
                    ],
                    'label'=>'Администратор',
                    'url'=>'#'
                ],
                [
                    'icon'=>'user-md',
                    'items'=>[
                        ['label'=>'Бланки осмотров', 'url'=>['/med/template']],
                        ['label'=>'Расшифровка ЭКГ', 'url'=>['/med/cardio']],
                        ['label'=>'Результаты тестов', 'url'=>['/med/test-result']],
                        ['label'=>'Рентгенография', 'url'=>['/med/rentgen']],
                        ['label'=>'Флюорография', 'url'=>['/med/flur']]
                    ],
                    'label'=>'Доктор',
                    'url'=>'#'
                ],
                [
                    'icon'=>'volume-control-phone',
                    'items'=>[
                        [
                            'label'=>'Бланки и заявки',
                            'items'=>[                                
                                ['label'=>'Бланки', 'url'=>['/med/proposal-blank']],
                                ['label'=>'Заявки', 'url'=>['/med/proposal']]
                            ],
                            'url'=>'#'
                        ],
                        ['label'=>'Мониторинг', 'url'=>['/med/monitor']],
                        ['label'=>'Мониторинг Архив', 'url'=>['/med/monitor/index-archive']]
                    ],
                    'label'=>'Колл-центр',
                    'url'=>'#'         
                ],
                [
                    'icon'=>'external-link',
                    'label'=>'На сайт',
                    'url'=>['/']
                ],
                [
                    'icon'=>'power-off',
                    'label'=>'Выход',
                    'template'=>'<a href="{url}" data-method="post">{icon}<span>{label}</span></a>',
                    'url'=>['/site/logout']
                ]
            ]
        ]) ?>
    </section>
</aside>