<?php
use yii\helpers\Html;

$this->title = 'Статистика';
?>

<div class="box box-body box-primary">
    <div class="row">
        <div class="col-md-12">
            <?= Html::a('Статистика проведенных консультаций по организациям', ['/statistic/org'], ['class'=>'btn btn-default btn-block btn-lg']) ?>
            <?= Html::a('Статистика проведенных консультаций по подразделениям', ['/statistic/dep'], ['class'=>'btn btn-default btn-block btn-lg']) ?>
            <?= Html::a('Статистика проведенных консультаций по сотрудникам', ['/statistic/doctor'], ['class'=>'btn btn-default btn-block btn-lg']) ?>
        </div>       
    </div>
</div>