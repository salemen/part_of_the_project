<?php
use yii\helpers\Html;
use app\widgets\Menu;

$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Экспресс-тесты'
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
                        ['label'=>'Тест на степень утомляемости', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/index']],
                        ['label'=>'Тест на внимательность к деталям', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/detail']],
                        ['label'=>'Тест на определение характера', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/character']],
                        ['label'=>'Тест на определение сильных и слабых сторон', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/sides']],
                        //['label'=>'Насколько Вы привлекательны как женщина?', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/woman']],
                        ['label'=>'Оптимист или пессимист?', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/optpes']]
                    ]
                ]) ?> 
            </div>
        </div>            
    </div>
    <div class="col-md-9">
        <div class="box box-body box-primary">
            <?= $this->render(Yii::$app->controller->action->id, [
                'model'=>isset($model) ? $model : false
            ]) ?>
        </div>
    </div>
</div>