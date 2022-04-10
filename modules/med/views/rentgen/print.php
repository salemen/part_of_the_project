<?php
use yii\helpers\Html;
?>

<div style="font-size: 20px; padding: 0 0 0 50px; width: 800px;">
    <div style="float: left;">
        <img src="/img/logo/logo-bird-xs.png" style="margin-top: 5px; width: 70%;">
    </div>    
    <div style="text-align: center;">
        <h2 style="margin-bottom: 0px;">ООО "Центр семейной медицины"</h2>
        <p style="margin: 5px 0;">Кабинет цифровой рентгенографии</p>
    </div>
    <div style="border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 3px 10px;">
        <table  align="center">
            <tr>
                <td style="padding-right: 18px;">ФИО пациента (ID):</td>
                <td><?= implode(' ', [$model->patient->u_fam, $model->patient->u_ima, $model->patient->u_otc]) . " ($model->r_n_medk)" ?></td>
            </tr>     
            <tr>
                <td>Пол:</td>
                <td><?= $model->patient->u_pol ?></td>
            </tr> 
            <tr>
                <td>Дата рождения:</td>
                <td><?= date('d.m.Y', strtotime($model->patient->u_data_ros)) ?></td>
            </tr>
            <tr>
                <td>Модель аппарата: </td>
                <td>JUMONG U</td>
            </tr>
        </table>
    </div>
    <div style="padding: 3px 10px;">
        <div>
            <b>Описание:</b> <?= $model->r_sakl_opis ?></br>
        </div>
        <div style="margin-top: 10px;">
            <b>Заключение:</b> <?= $model->r_sakl ?>
        </div>
        <?= ($model->r_eed) ? Html::tag('div', implode(' ', [Html::tag('b', 'ЭЭД:'), $model->r_eed, 'м3в']), ['style'=>'margin-top: 10px;']) : null ?>
    </div>
    <div style="padding: 30px 10px 0;">
        <div style="float: left; width: 70%;">
            <b>Врач-рентгенолог:</b> <?= $model->r_sakl_vrach ?> <span style="margin-left: 15px;"> ___________________ </span>
        </div>
        <div style="float: left; text-align: right; width: 30%;">
            <?= date('d.m.Y H:i (МСК)', strtotime($model->r_data)) ?>
        </div> 
    </div>
</div>

<?php
$this->registerJs('
    print();
');