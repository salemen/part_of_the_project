<?php
use yii\helpers\Html;

$this->title = $model->name;
$this->params['breadcrumbs'][] = $this->title;
        
$user = Yii::$app->user;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div class="row">
                <div class="col-md-6 lead">
                    <?= $model->desc ?>
                </div>
                <div class="col-md-6">	  
                    <?= Html::img('/uploads/' . $model->img, ['class'=>'img-responsive']) ?>
                </div>    
                <div class="col-md-12" style="margin-top: 20px; text-align: center;">
                    <?= (!Yii::$app->user->isGuest) ? Html::a('Вход / Регистрация', '#', ['class'=>'btn btn-lg btn-danger btn-login']) : Html::a('Пройти тест', ['/site/test', 'test_id'=>$model->id], ['class'=>'btn btn-lg btn-danger']) ?>
                </div>    
            </div>
        </div>
    </div>
</div>


