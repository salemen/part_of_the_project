<?php
use yii\helpers\Html;

$this->title = 'Мониторинг ОРВИ/COVID-19: Статистика';
$this->params['breadcrumbs'][] = ['label'=>'Наблюдение онлайн', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
        <?= $this->render('_part/alert', ['model'=>$modelLast]) ?>
        <?= $this->render('_part/table', ['model'=>$model]) ?> 
        <div style="text-align: center; margin-top: 20px;">
            <?= Html::a('Заполнить протокол заново', ['protocol', 'type'=>$type], ['class'=>'btn btn-success btn-lg']) ?>
            <?= ($count > 1) ? Html::a('Показать график', '#collapseOne', ['class'=>'btn btn-primary btn-lg collapsed', 'data'=>['parent'=>'#accordion', 'toggle'=>'collapse']]) : null ?>           
        </div>  
        <?= ($count > 1) ? $this->render('_part/chart', ['data'=>$data]) : null ?>
    </div>
</div> 

<?php
$this->registerCss('
.content-wrapper {
    background-color: #FFFFFF;
    background-image: url(/img/covid/bg1.png);
    background-position: center;
    background-repeat: no-repeat;
    background-size: inherit;
}
');