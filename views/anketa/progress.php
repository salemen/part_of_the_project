<?php
use yii\helpers\Html;
?>

<div class="row">
    <div class="col-md-10 col-md-offset-1" style="margin-bottom: 20px; text-align: center;">
        <p>Вопрос <?= $position ?> из <?= $count ?></p>
        <div class="progress">            
            <?= Html::tag('div', null, [
                'class'=>'progress-bar progress-bar-striped active', 
                'id'=>'anketa-progress',
                'style'=>['width'=>$progress . '%']                  
            ]) ?>
        </div>
    </div>
</div> 