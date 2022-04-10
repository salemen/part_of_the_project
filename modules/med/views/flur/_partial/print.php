<div style="width: 800px; padding: 0 0 0 50px;">
    <div style="text-align: center;">
        <h2>Лист выдачи результатов флюорографического обследования органов грудной клетки</h2>
    </div>
    <div style="border-bottom: 1px solid #000; border-top: 1px solid #000; padding: 0 0 0 10px;"> 
        ФИО: <?= implode(' ', [$model->patient->u_fam, $model->patient->u_ima, $model->patient->u_otc]) ?>;
        Пол: <?= $model->patient->u_pol ?>;
        Год рождения: <?= date('d.m.Y', strtotime($model->patient->u_data_ros))?>;
        Номер мед. карты: <?= $model->f_n_medk ?>;
        </br>Предприятие: <?= $model->f_organis ?>;
        </br>Модель аппарата: 12ФК1 Флюарком; ЭЭД: 0,1 мЗв.
    </div>
    <div style="padding: 5px 0 0 10px;">
        Дата обследования: <?= date('d.m.Y', strtotime($model->f_data)) ?></br>
        <u>Описание:</u> <?= $model->f_sakl_opis ?></br>
        <u>Заключение:</u> <?= $model->f_sakl ?></br>
    </div>
    <div style="padding: 30px 0 0 10px;">
        Врач-рентгенолог: <?= $model->f_sakl_vrach ?> ___________________
    </div>
</div>

<?php
$this->registerJs('
    print();
');