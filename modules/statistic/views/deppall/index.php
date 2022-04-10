<?php
use yii\helpers\Html;
use app\assets\ChartJsAsset;

ChartJsAsset::register($this);

$this->title = 'Статистика';
$this->params['breadcrumbs'][] = ['label'=>'Статистика', 'url'=>['/statistic']];
$this->params['breadcrumbs'][] = $title;
?>

    <div class="box box-body box-primary">
        <div class="row">
            <div class="col-md-12">
                <?= Html::tag('h4', $title, ['style'=>'text-align: center;']) ?>
                <?= $this->render('/_chart/doughnut-picker', [
                    'data'=>$data,
                    'showOrg'=>true,
                    'org'=>$org,
                    'showPeriod'=>true,
                    'period'=>$period
                ]) ?>
            </div>


            <div class="col-md-12" style="margin-top: 30px; text-align: center;">
                <?= Html::a('Вернуться назад', ['/statistic'], ['class'=>'btn btn-danger']) ?>
            </div>
        </div>
    </div>


