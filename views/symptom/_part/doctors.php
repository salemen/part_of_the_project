<?php
use yii\helpers\Html;
use app\models\employee\Employee;
?>
        
<div class="row" style="margin-top: 20px;">
    <hr>
    <h4 class="text-center" style="margin-bottom: 20px;">В решении этого вопроса вам могут помочь:</h4>
    <?php foreach ($model as $value) {
        echo Html::beginTag('div', ['class'=>'col-md-2 text-center', 'style'=>'margin-bottom: 15px;']);
            $div = Html::tag('div', null, ['class'=>'bg-img-center', 'style'=>['background-image'=>'url('. Employee::getProfilePhoto($model).')', 'height'=>'230px']]);
            echo Html::a($div . $value->fullname, ['/doctor/view', 'id'=>$value->id]);
        echo Html::endTag('div');
    } ?>
</div>