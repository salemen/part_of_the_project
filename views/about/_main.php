<?php
use yii\helpers\Html;
use app\widgets\Menu;

$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Информация об Онлайн-Поликлинике: основные сведения, документы, контакты, структура и миссия и т.д.'
], 'description');
?>

<div class="row">
    <?= Html::a('<i class="fa fa-chevron-right"></i>', '#', ['class'=>'btn-aside-toggle']) ?>
    <div class="aside-column col-md-3">
        <div id="affixBlock">
            <?= Html::a('<i class="fa fa-remove"></i>', '#', ['class'=>'btn-aside-toggle-mobile']) ?>
            <div class="box box-body box-primary">
                <?= Menu::widget([
                    'defaultIconHtml'=>null,
                    'options'=>['class'=>'list-group list-group-unbordered'],
                    'items'=>[
                        ['label'=>'О сервисе', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/service']],
                        ['label'=>'Контакты', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/contact']],
                        ['label'=>'Нормативно-правовые документы', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/documents']],
                        ['label'=>'Лицензии', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/license']],
                        ['label'=>'Вакансии', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/vacancy']],
                        ['label'=>'Часто задаваемые вопросы', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/questions']],
                        ['label'=>'Соглашения', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/info']],
                        ['label'=>'Инструкция', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/patient-help']],
                        ['label'=>'Безопасность платежей', 'options'=>['class'=>'list-group-item'], 'url'=>['/about/paysecure']],
                    ]
                ]) ?> 
            </div>
        </div>            
    </div>
    <div class="col-md-9">
        <div class="box box-body box-primary">
            <?= $this->render(Yii::$app->controller->action->id) ?>
        </div>
    </div>
</div>