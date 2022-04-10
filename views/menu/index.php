<?php
use app\widgets\MenuItems;

$this->title = $model->name;
?>

<div class="row">    
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?= MenuItems::widget(['model'=>$model, 'showHeader'=>false]) ?>
        </div>
    </div>
</div>