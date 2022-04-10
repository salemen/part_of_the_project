<?php
use app\widgets\Menu;
use app\modules\covid\models\CovidPages;

$user = Yii::$app->user->identity;

function pushItems($page) {    
    return [
        'label'=>$page->name,        
        'url'=>["/covid/{$page->controller}/page", 'slug'=>$page->action]
    ];
}

$faqItems = [];
$hospitalItems = [];
$pages = CovidPages::find()->where(['status'=>10])->orderBy('name')->all();
if ($pages) {
    foreach ($pages as $key=>$page) {
        switch ($page->controller) {
            case 'faq':
                $faqItems[$key] = pushItems($page);
                break;
            case 'hospital':
                $hospitalItems[$key] = pushItems($page);
                break;
            default:
                break;
        }
    }
}

array_push($hospitalItems, [
    'label'=>'Респираторные госпитали',
    'url'=>['/covid/info/map', 'type'=>'hospital']
]);
?>

<aside class="main-sidebar">
    <section class="sidebar" style="padding-top: 20px;">
        <?= Menu::widget([
            'defaultIconHtml'=>null,
            'encodeLabels'=>false,
            'items'=>[     
                [
                    'items'=>$faqItems,
                    'label'=>'<img src="/sars/icon-min/faq.png"> Актуальные вопросы и ответы',
                    'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'],
                    'options'=>['class'=>'dropdown'],
                    'visible'=>!empty($faqItems),
                    'url'=>'#'                                
                ],
                [
                    'label'=>'<img src="/sars/icon-min/info.png"> Официальная информация',
                    'url'=>['/covid/info']
                ],
//                [
//                    'label'=>'<img src="/sars/icon-min/hotline.png"> Горячая линия COVID-19',
//                    'url'=>['/covid/hotline']
//                ],
//                [
//                    'label'=>'<img src="/sars/icon-min/library.png"> Библиотека COVID-19',
//                    'url'=>['/covid/library']
//                ],
                [
                    'items'=>[
                        [
                            'label'=>'Проверка рез-тов теста на COVID-19',
                            'url'=>['/covid/diagnosis']
                        ],
                        [
                            'label'=>'Где сделать КТ?',
                            'url'=>['/covid/info/map', 'type'=>'test']
                        ],
                        [
                            'label'=>'Где сделать тест на COVID-19?',
                            'url'=>['/covid/info/map', 'type'=>'vaccine']
                        ]
                    ],
                    'label'=>'<img src="/sars/icon-min/diagnosis.png"> Диагностика COVID-19',
                    'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'],
                    'options'=>['class'=>'dropdown'],
                    'url'=>'#'
                ],
                [
                    'label'=>'<img src="/sars/icon-min/consult.png"> Консультация',
                    'linkOptions'=>['target'=>'_blank'],
                    'url'=>['/doctor-special']
                ],
                [
                    'items'=>$hospitalItems,
                    'label'=>'<img src="/sars/icon-min/hospital.png"> Госпитализация',
                    'linkOptions'=>['class'=>'dropdown-toggle', 'data-toggle'=>'dropdown'],
                    'options'=>['class'=>'dropdown'],
                    'visible'=>!empty($hospitalItems),
                    'url'=>'#' 
                ],
//                [
//                    'label'=>'<img src="/sars/icon-min/pills.png"> Лекарственное обеспечение',
//                    'url'=>'#'
//                ],
//                [
//                    'label'=>'<img src="/sars/icon-min/vaccine.png"> Вакцинация',
//                    'url'=>'#'
//                ],
                [
                    'label'=>'<img src="/sars/icon-min/monitor.png"> Мониторинг',
                    'linkOptions'=>['target'=>'_blank'],
                    'url'=>['/monitor']
                ]
            ],
            'options'=>['class'=>'sidebar-menu tree', 'data-widget'=>'tree']
        ]) ?>
    </section>
</aside>

<?php
$this->registerCss('
.content-wrapper {
    margin-left: 350px;
}    
.main-header {
    border-bottom: 1px solid #eee;
}
.main-header .logo {
    width: 350px;
}
.main-header .navbar {
    margin-left: 350px;
}
.main-header .sidebar-toggle:hover {
    color: #193e85;
}
.main-sidebar {
    background-color: #ffffff;
    border-right: 1px solid #eee;
    width: 350px;
}
.sidebar-menu > li > a, .treeview-menu > li > a {
    color: #333333;
    font-size: 17px;
}
.sidebar-menu > li:not(.dropdown).active > a, .treeview-menu > li.active > a {
    font-weight: 600;
}
.treeview-menu {
    padding-left: 32px;
}
.treeview-menu > li {
    font-weight: 500;
}

@media (max-width: 767px) {
    .content-wrapper {
        margin-left: 0;
        padding-top: 52px !important;
    }
    .main-header .navbar {
        margin: 0;        
    }
    .main-header .logo, .main-header .navbar {
        font-size: 17px;
        width: 80%;
    }
    .main-sidebar {
        -webkit-transform: translate(-350px, 0);
        -ms-transform: translate(-350px, 0);
        -o-transform: translate(-350px, 0);
        border-right: none;
        padding-top: 52px !important;
        transform: translate(-350px, 0);
        width: 350px;
    }
    .sidebar-open .content-wrapper, .sidebar-open .main-footer {
        -webkit-transform: translate(350px, 0);
        -ms-transform: translate(350px, 0);
        -o-transform: translate(350px, 0);
        transform: translate(350px, 0);
    }
}
');