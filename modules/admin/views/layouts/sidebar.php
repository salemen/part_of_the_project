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
                    'icon'=>'cog',
                    'items'=>[
                        ['label'=>'Анкеты', 'url'=>['/admin/anketa']],
                        [
                            'label'=>'Меню', 
                            'items'=>[
                                ['label'=>'Разделы меню', 'url'=>['/admin/menu-section']],
                                ['label'=>'Пункты меню', 'url'=>['/admin/menu-item']]
                            ],
                            'url'=>'#'
                        ],
                        ['label'=>'Роли и разрешения', 'url'=>['/admin/employee-roles']],
                        ['label'=>'Сервисные тесты',  'url'=>['/admin/servtest']],
                        ['label'=>'Слайдер', 'url'=>['/admin/slider']],
                        ['label'=>'Тесты', 'url'=>['/admin/test']],
                        ['label'=>'COVID-19 Карты', 'url'=>['/admin/covid-maps']],
                        ['label'=>'COVID-19 Страницы', 'url'=>['/admin/covid-pages']]
                    ],
                    'label'=>'Администрирование',  
                    'visible'=>Yii::$app->user->can('admin'),
                    'url'=>'#'                    
                ],                  
                [
                    'icon'=>'user-md',
                    'items'=>[
                        ['label'=>'Консультанты', 'url'=>['/admin/employee-advisor']],
                        ['label'=>'Консультации', 'url'=>['/admin/consult']]
                    ],
                    'label'=>'Менеджер',  
                    'visible'=>Yii::$app->user->can('manager'),
                    'url'=>'#'                    
                ],
                [
                    'icon'=>'pencil-square-o',
                    'items'=>[
                        [
                            'label'=>'Мед. деятельность',
                            'items'=>[                                
                                ['label'=>'Направления', 'url'=>['/admin/medical-section']]
                            ],
                            'url'=>'#'
                        ],
                        [
                            'label'=>'Результаты анализов',
                            'items'=>[                                
                                ['label'=>'Виды исследований', 'url'=>['/admin/research-type']],
                                ['label'=>'Методы исследований', 'url'=>['/admin/research-method']],
                                ['label'=>'Единицы измерения', 'url'=>['/admin/research-unit']]
                            ],
                            'url'=>'#'
                        ],
                        [
                            'label'=>'Симптомы и болезни',
                            'items'=>[     
                                ['label'=>'Категории', 'url'=>['/admin/checker-bodyparts']],
                                ['label'=>'Симптомы и болезни', 'url'=>['/admin/checker-symptoms']]
                            ],
                            'url'=>'#'
                        ]
                    ],
                    'label'=>'Методист',  
                    'visible'=>Yii::$app->user->can('methodist'),
                    'url'=>'#'                    
                ],
                [
                    'icon'=>'sitemap',
                    'items'=>[
                        ['label'=>'Консультанты', 'url'=>['/admin/seo']],
                        ['label'=>'Описание страниц', 'url'=>['/admin/page-config']]
                    ],
                    'label'=>'SEO', 
                    'visible'=>Yii::$app->user->can('seo'),
                    'url'=>'#'                    
                ],
                [
                    'icon'=>'info-circle',
                    'items'=>[                        
                        ['label'=>'Организации', 'url'=>['/admin/organization']],
                        ['label'=>'Пациенты', 'url'=>['/admin/patient']],
                        ['label'=>'Сотрудники (офиц.)', 'url'=>['/admin/employee/index']],
                        ['label'=>'Сотрудники (неофиц.)', 'url'=>['/admin/employee/index2']],
                        ['label'=>'Сотрудники (сторонние)', 'url'=>['/admin/employee/index3']]
                    ],
                    'label'=>'Справочник',
                    'visible'=>Yii::$app->user->can('doctor'),
                    'url'=>'#'
                ],
                [
                    'icon'=>'bar-chart',
                    'items'=>[                        
                        ['label'=>'Консультации', 'url'=>['/admin/statistic/consult']],
                        ['label'=>'Пациенты', 'url'=>['/admin/statistic/patient']],
                        ['label'=>'Онлайн-платежи', 'url'=>['/admin/statistic/payments-online']]
                    ],
                    'label'=>'Статистика',
                    'visible'=>Yii::$app->user->can('statist'),
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