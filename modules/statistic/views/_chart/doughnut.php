<?php
use yii\helpers\Html;
?>
        
<div style="margin-top: 20px;">
    <?= ($data) ? Html::tag('div', Html::tag('canvas', null, ['id'=>'doughnutStat']), ['class'=>'chart']) : 'Данных не найдено. Измените условия поиска или попробуйте позже.' ?>
</div>