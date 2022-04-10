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
                    'icon'=>'hospital-o',
                    'label'=>'Организации', 
                    'url'=>['/b2b/organization']                    
                ],
                [
                    'icon'=>'user-md',
                    'label'=>'Сотрудники', 
                    'url'=>['/b2b/employee']                    
                ],
                [
                    'icon'=>'comments-o',
                    'items'=>[
                        ['label'=>'Консультанты', 'url'=>['/b2b/employee-advisor']],
                        ['label'=>'Статистика', 'url'=>'#', 'items'=>[
                            ['label'=>'По организациям график', 'url'=>['/b2b/statistic-org']],
                            ['label'=>'По организациям таблица', 'url'=>['/statistic/dep-all/index']],
                            ['label'=>'По подразделениям', 'url'=>['/b2b/statistic-dep']],
                            ['label'=>'По сотрудникам', 'url'=>['/b2b/statistic-empl']],
                            ['label'=>'По сотруднику', 'url'=>'#', 'items'=>[
                                ['label'=>'Статистика', 'url'=>['/b2b/consult-one']],
                                ['label'=>'Удержание', 'url'=>['/b2b/consult-one/indexhold']],
                            ]
                            ]]
                        ]],
                    'label'=>'Консультации',
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

<?php
$this->registerCss('
.content-wrapper {
    margin-left: 320px;
}    
.main-header .logo {
    height: 51px;
    width: 320px;
}
.main-header .navbar {
    margin-left: 320px;
}
.main-sidebar {
    width: 320px;
}
.user-panel {
    border-top: 1px solid #ddd;    
}
.user-panel > .image > img {    
    height: auto;
    max-width: 45px;
    width: 100%;
}
.user-panel > .info {
    font-weight: 500 !important;
    line-height: 1;
    padding: 1px 2px 1px 15px;    
    position: absolute;
    left: 55px;
    width: 255px;
}
.user-panel > .info > p {
    font-weight: 500;
}
.user-panel.active > .info {
    color: #ffffff !important;
}
.user-panel.active {
    background-color: #3c8dbc;    
}

@media (max-width: 767px) {
    .content-wrapper {
        margin-left: 0;
    }
    .main-header .navbar {
        margin: 0;
    }
    .main-header .logo, .main-header .navbar {
        width: 100%;
    }
    .main-sidebar {
        -webkit-transform: translate(-320px, 0);
        -ms-transform: translate(-320px, 0);
        -o-transform: translate(-320px, 0);
        transform: translate(-320px, 0);
        width: 320px;
    }
    .sidebar-open .content-wrapper, .sidebar-open .main-footer {
        -webkit-transform: translate(320px, 0);
        -ms-transform: translate(320px, 0);
        -o-transform: translate(320px, 0);
        transform: translate(320px, 0);
    }
}
');