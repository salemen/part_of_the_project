<?php
use yii\helpers\Html;
use app\widgets\Menu;

$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Оценка зрения'
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
                        ['label'=>'Тест близорукости/дальнозоркости', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/vision']],
                        ['label'=>'Тест на астигматизм', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/astigmatism']],
                        ['label'=>'Тест на цветовосприятие', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/color']],
                        ['label'=>'Тест Амслера', 'options'=>['class'=>'list-group-item'], 'url'=>['/express-test/amsler']]
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

