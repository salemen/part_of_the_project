<?php
use yii\helpers\Html;
?>
        
<div class="col-md-10 col-md-offset-1" style="margin-top: 30px; text-align: center;">
    <?= ($data) ? Html::tag('div', Html::tag('canvas', null, ['id'=>'doughnutStat']), ['class'=>'chart']) : 'Данных не найдено. Измените условия поиска или попробуйте позже.' ?>
</div>