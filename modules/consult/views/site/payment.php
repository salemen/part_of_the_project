<?php
use yii\helpers\Html;
use app\models\consult\Consult;

$this->title = 'Оплата консультации';
?>

<div class="row" style="margin-top: 10px;">
    <div class="col-md-12"> 
        <h4 class="text-primary" style="font-size: 24px; margin-top: 0px;"><?= $model->employee->fullname ?></h4>
        <?php
        if (Yii::$app->params['specialConsult']['active']) {
            $cons = ($model->is_special) ? Yii::$app->params['specialConsult']['name'] : (Consult::isConsultSecond($model->employee_id) ? 'Стоимость повторной консультации' : 'Стоимость консультации');
            $cost = ($model->is_special) ? Yii::$app->params['specialConsult']['cost'] : Consult::getConsultCost($model->employee->advisor);                        
        } else {
            $cons = Consult::isConsultSecond($model->employee_id) ? 'Стоимость повторной консультации' : 'Стоимость консультации';
            $cost = Consult::getConsultCost($model->employee->advisor);
        }
        if ($cost) {
            $text = Html::tag('span', ($cost === 0) ? 'Бесплатно' : $cost . ' руб.', ['class'=>'text-danger', 'style'=>'font-size: 22px; font-weight: 600;']);
            echo Html::tag('p', $cons . ':<br>' . $text, ['class'=>'price']);
        } ?>
        <ul>
            <li>Консультация врача будет проведена в формате переписки</li>
            <li>Максимальное ожидание ответа менее 72 часов</li>
            <li>Время общения ограничено только моментом установления рекомендаций</li>
            <li><span style="color: #e7505a;">Внимание!</span> После оплаты, отменить консультацию невозможно</li>
            <li>Продолжая я соглашаюсь на условия оказания платных услуг</li>
        </ul>
        <hr>
        <div class="text-center">
            <?= Html::a(($cost === 0) ? 'Подтвердить консультацию' : 'Оплатить консультацию', ['consult-pay'], ['class'=>'btn btn-primary consult-pay', 'data'=>['consult_id'=>$model->id]]) ?>
            <?= Html::a('Отменить', ['consult-cancel'], ['class'=>'btn btn-danger consult-cancel', 'data'=>['consult_id'=>$model->id]]) ?>
        </div>
    </div>
</div>

<?php
$this->registerJs('
$(document).on("click", ".consult-pay", function(e) {
    var consult_id = $(this).data("consult_id");
    var url = $(this).attr("href");
    
    $.ajax({        
        data: { consult_id: consult_id },
        method: "post",
        url: url
    });
    
    e.preventDefault();
});

$(document).on("click", ".consult-cancel", function(e) {
    var consult_id = $(this).data("consult_id");
    var url = $(this).attr("href");
    
    $.ajax({
        data: { consult_id: consult_id },
        method: "post",
        url: url
    });
    
    e.preventDefault();
});
');