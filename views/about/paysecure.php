<?php
use yii\helpers\Html;

$this->title = 'Безопасность платежей';
$this->params['breadcrumbs'][] = ['label'=>'Информация', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-md-12">
        <div class="row" style="margin-bottom: 15px;">
            <div class="col-md-3">
                <?= Html::img('/img/pay/logo-best2pay.png', ['class'=>'img-center-responsive', 'style'=>'max-height: 50px;']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::img('/img/pay/logo-visa.png', ['class'=>'img-center-responsive', 'style'=>'max-height: 50px;']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::img('/img/pay/logo-mastercard.png', ['class'=>'img-center-responsive', 'style'=>'max-height: 50px;']) ?>
            </div>
            <div class="col-md-3">
                <?= Html::img('/img/pay/logo-mir.png', ['class'=>'img-center-responsive', 'style'=>'max-height: 50px;']) ?>
            </div>
        </div>
        <div>
            <p>Оплатить заказ можно с помощью банковских карт платёжных систем Visa, MasterCard, МИР. 
                При оплате банковской картой безопасность платежей гарантирует процессинговый центр
                <?= Html::a('Best2Pay', 'https://www.best2pay.net/', ['target'=>'_blank']) ?>.
            </p>
            <p>Приём платежей происходит через защищённое безопасное соединение, используя протокол TLS 1.2. 
                Компания <?= Html::a('Best2Pay', 'https://www.best2pay.net/', ['target'=>'_blank']) ?> соответствует международным требованиями PCI DSS для 
                обеспечения безопасной обработки реквизитов банковской карты плательщика. 
                Ваши конфиденциальные данные необходимые для оплаты (реквизиты карты, регистрационные данные и др.) не поступают в 
                Интернет-магазин, их обработка производится на стороне процессингового центра
                <?= Html::a('Best2Pay', 'https://www.best2pay.net/', ['target'=>'_blank']) ?> и полностью защищена. Никто, в том числе интернет-магазин
                САНТАЛЬ Онлайн-Поликлиника, не может получить банковские и персональные данные плательщика.
            </p>
            <p>При оплате заказа банковской картой возврат денежных средств производится на ту же самую карту, с которой был произведён платёж.</p>
        </div>
    </div>
</div>