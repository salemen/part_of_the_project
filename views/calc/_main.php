<?php
use yii\helpers\Html;
use app\widgets\Menu;

$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Расчет факторов риска. Калькуляторы здоровья'
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
                        ['label'=>'Вычисление индекса массы тела', 'options'=>['class'=>'list-group-item'], 'url'=>['/calc/index']],
                        ['label'=>'Вычисление идеальной массы тела', 'options'=>['class'=>'list-group-item'], 'url'=>['/calc/brok']],
                        ['label'=>'Вычисление суточной нормы калорий', 'options'=>['class'=>'list-group-item'], 'url'=>['/calc/calory']],
                        ['label'=>'Вычисление индекса курильщика', 'options'=>['class'=>'list-group-item'], 'url'=>['/calc/smoky']]
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