<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use app\helpers\AppHelper;

$this->title = 'Оплата заказа';
$this->params['breadcrumbs'][] = ['label'=>'Онлайн-платежи', 'url'=>['index']];
$this->params['breadcrumbs'][] = $this->title;

$fullname = implode(' ', [$patient['Фамилия'], $patient['Имя'], $patient['Отчество']]);
$user_birth = date('d.m.Y г.', strtotime($patient['ДатаРождения']));

function getStatusRaw($needPay)
{
    $class = ($needPay) ? 'text-danger' : 'text-success';
    $text = ($needPay) ? 'Ожидает оплаты' : 'Оплачен';

    return Html::tag('span', $text, ['class'=>$class]);
}
?>

<section class="invoice">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-globe"></i> САНТАЛЬ Онлайн-Поликлиника
            </h2>
        </div>
    </div>
    <div class="row invoice-info">
        <div class="col-sm-4 invoice-col">
            Плательщик
            <address>
                <strong><?= AppHelper::secretFullname($fullname) ?></strong><br>
                Дата рождения: <?= $user_birth ?><br>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            Исполнитель
            <address>
                <?php if ($performer !== null && isset($performer['Description'])) { ?>
                    <strong><?= $performer['Description'] ?></strong><br>
                    <?= $performer['АдресПодразделения'] ?><br>
                <?php } else { ?>
                    <strong>ООО "ЦСМ"</strong><br>
                    634059, г. Томск, ул. Смирнова, д. 30<br>
                    ИНН / КПП: 7017135954 / 701701001<br>
                    ОГРН / ОКПО: 1067017007188 / 79197187<br>
                <?php } ?>
            </address>
        </div>
        <div class="col-sm-4 invoice-col">
            <b>Заказ: #<?= $model['Штрихкод'] ?></b><br>
            <br>
            <b>Дата заказа:</b> <?= date('d.m.Y', strtotime($model['ДатаЗаказа'])) ?><br>
            <b>Оплатить до:</b> <?= date('d.m.Y', strtotime($model['ДатаЗаказа']) + 7 * 86400) ?> (включительно)<br>
            <b>Статус:</b> <?= getStatusRaw($model['НаОплату']) ?><br><br>
        </div>
      </div>

    <div class="row">
        <div class="col-sm-12 table-responsive">
            <?= GridView::widget([
                'dataProvider'=>$dataProvider,
                'columns'=>[
                    ['class'=>'kartik\grid\SerialColumn'],                    
                    [
                        'attribute'=>'artikul',
                        'header'=>'Артикул'
                    ],
                    [
                        'attribute'=>'name',
                        'header'=>'Наименование услуги'
                    ],
                    [
                        'attribute'=>'cost',
                        'header'=>'Стоимость'
                    ]                    
                ]
            ]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-4 col-md-offset-8">
            <div class="table-responsive">
                <table class="table">
                    <tbody>
                        <tr>
                            <th style="width: 50%;">Итоговая сумма:</th>
                            <td><?= $model['СуммаДокумента'] ?> руб.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <?php if ($model['НаОплату'] && $model['СуммаДокумента'] !== 0) { ?>
        <div class="row">
            <div class="col-xs-12">
                <?= Html::a('<i class="fa fa-credit-card"></i> Перейти к оплате', ['process'],
                    [
                        'class'=>'btn btn-success pull-right', 
                        'data'=>[
                            'method'=>'post',
                            'params'=>[
                                'service_id'=>$model['Ref_Key'],
                                'user_id'=>$model['Пациент_Key'],
                                'sum'=>$model['СуммаДокумента']
                            ]
                        ]
                    ]
                ) ?>
            </div>
        </div>
    <?php } ?>
</section>

<section class="invoice">
    <div class="row">
        <div class="col-xs-12">
            <h2 class="page-header">
                <i class="fa fa-info"></i> Безопасность платежей
            </h2>
            
            <div class="hidden-xs">
                <?= Html::img('/img/pay/logo-visa.png', ['alt'=>'Visa', 'style'=>'margin-right: 10px; max-height: 30px;']) ?>
                <?= Html::img('/img/pay/logo-mastercard-s.png', ['alt'=>'Mastercard', 'style'=>'margin-right: 10px; max-height: 30px;']) ?>
                <?= Html::img('/img/pay/logo-mir.png', ['alt'=>'MIR', 'style'=>'margin-right: 10px; max-height: 30px;']) ?>
            </div>    
            
            <blockquote style="margin-top: 20px;">    
                <p>
                    Оплатить заказ можно с помощью банковских карт платёжных систем Visa, MasterCard, МИР.<br>
                    Приём платежей происходит через защищённое безопасное соединение, используя протокол TLS 1.2.<br>
                    При оплате заказа банковской картой возврат денежных средств производится на ту же самую карту, с которой был произведён платёж.
                </p>
            </blockquote>
        </div>
    </div>    
</section>