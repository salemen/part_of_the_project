<?php
use yii\helpers\Html;
use app\helpers\AppHelper;
use app\widgets\Menu;
use app\models\CommonUser;
use app\models\anketa\AnketaSession;
use app\models\test\Test;
use app\models\user\UserDocs;

$user = Yii::$app->user;

function getTests($user) {
    $tests = Test::find()->all();

    foreach ($tests as $test) {
        $items[] = ['label'=>$test->name, 'url'=>['/user/test', 'test_id'=>$test->id]];
    }

    return $items;
}

function getAnkets($user) {
    $items[] = ['label'=>'Анкеты ' . '(' . AnketaSession::getAnketasCount($user->id) . ')', 'url'=>'/user/anketa'];

    return $items;
}
?>

<aside class="main-sidebar">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <?= Html::img(CommonUser::getPhoto($user->id)) ?>
            </div>
            <div class="pull-left info">
                <p><?= AppHelper::shortFullname($user->identity->fullname) ?></p>
                <span><i class="fa fa-circle text-success"></i> Online</span>
            </div>
        </div>
        <?= Menu::widget([
            'options'=>['class'=>'sidebar-menu tree', 'data-widget'=>'tree'],
            'items'=>[
                [
                    'icon'=>'user-o',
                    'label'=>'Общие сведения',
                    'url'=>['/user/profile']
                ],
                [
                    'icon'=>'pencil-square-o',
                    'label'=>'Анкеты',
                    'items'=>getAnkets($user),
                    'url'=>['#']                  
                ],
                [
                    'icon'=>'list-ul',
                    'label'=>'Тесты',
                    'items'=>getTests($user),
                    'url'=>['#']                    
                ],
                [
                    'icon'=>'eyedropper',
                    'label'=>'Вакцинация',
                    'url'=>['/user/vaccines']
                ],
                [
                    'icon'=>'stethoscope',
                    'label'=>'Диагнозы',
                    'url'=>['/user/diagnosis']
                ],                
                [
                    'icon'=>'file-o',
                    'label'=>'Документы (' . UserDocs::getDocsCount($user->id) . ')',
                    'url'=>['/user/docs']
                ],                
                [
                    'icon'=>'comments-o',
                    'label'=>'Консультации',
                    'items'=>[
                        [
                            'label'=>'Мои консультации',                    
                            'url'=>['/consult']
                        ],
                        [
                            'label'=>'Мой доход',
                            'visible'=>$user->isAdvisor,
                            'url'=>['/user/profile/profit']
                        ]
                    ],
                    'url'=>['#']                    
                ], 
                [
                    'icon'=>'thermometer-full',
                    'label'=>'Результаты анализов',
                    'url'=>['/user/analysis']                    
                ],
                [
                    'icon'=>'line-chart',
                    'label'=>'Физические данные',
                    'url'=>['/user/user-params']
                ],
                [
                    'icon'=>'heart-o',
                    'label'=>'ЭКГ описание',
                    'url'=>['/user/cardio']
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