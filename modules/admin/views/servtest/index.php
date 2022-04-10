<?php
use yii\helpers\Html;

$this->title = 'Сервисные тесты';
$this->params['dashboard'][] = true;
?>

<div class="row">    
    <div class="col-md-3">
        <div class="box box-body box-blue">    
            <?= Html::tag('h4', 'Тестирование E-mail') . $this->render('_form/email', ['model'=>$emailModel]) ?> 
        </div>        
    </div>
    <div class="col-md-3">
        <div class="box box-body box-blue">    
            <?= Html::tag('h4', 'Тестирование СМС') . $this->render('_form/sms', ['model'=>$smsModel]) ?> 
        </div>        
    </div>
</div>