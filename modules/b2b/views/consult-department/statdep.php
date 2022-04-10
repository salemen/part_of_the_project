<?php
$this->title = $title;
$this->params['breadcrumbs'][] = 'Статистика';
?>

<?= $this->render('/_chart/doughnut-picker', [
    'data'=>$data,
    'showOrg'=>true,
    'org'=>$org,
    'showPeriod'=>true,
    'period'=>$period
]) ?>