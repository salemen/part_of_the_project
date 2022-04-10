<?php
use yii\helpers\Html;
use app\models\consult\Consult;
use app\models\employee\Employee;

?>

    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-md-4">
                    <div class="bg-img-center"
                         style="height: 200px; width: 160px; background-image: url(<?= Employee::getProfilePhoto($model) ?>)"></div>
                </div>
                <div class="col-md-8">
                    <h4 class="text-primary" style="font-size: 24px; margin-top: 0px;"><?= $model->fullname ?></h4>
                    <p style="font-size: 17px;">
                        <?php
                        if (($cps = $model->customPositions) !== null) {
                            foreach ($cps as $cp) {
                                echo Html::tag('span', $cp->value, ['style' => 'font-weight: 600;']) . '<br>';
                            }
                        }
                        if ($model->positionsDoctor) {
                            foreach ($model->positionsDoctor as $pos) {
                                echo '<span style="font-weight: 600;">' . $pos->empl_pos . '</span><br>';
                            }
                            echo '<br>';
                        }
                        if ($model->degrees) {
                            foreach ($model->degrees as $degree) {
                                echo '<span>' . $degree->empl_degree . '</span><br>';
                            }
                        }
                        if ($model->ranks) {
                            foreach ($model->ranks as $rank) {
                                if ($rank->empl_rank) {
                                    echo '<span>' . $rank->empl_rank . '</span><br>';
                                }
                            }
                        }
                        ?>
                    </p>
                </div>
                <div class="col-md-12">
                    <p class="price">
                        <?php
                        $cost = Consult::getConsultCost($model->advisor);
                        if ($cost) {
                            $cons = Consult::isConsultSecond($model->id) ? 'Стоимость повторной консультации' : 'Стоимость консультации';
                            $text = Html::tag('span', ($cost === 0) ? 'Бесплатно' : $cost . ' руб.', ['class' => 'text-danger', 'style' => 'font-size: 22px; font-weight: 600;']);
                            echo Html::tag('p', $cons . ':<br>' . $text, ['class' => 'price']);
                        } ?>
                    </p>
                    <ul>
                        <?php if ($model->fullname == "Бут Александра Валерьевна"){ ?>
                            <li>Онлайн-консультация врача-психиатра Бут А.В. доступна только для пациентов,
                                находящихся на лечении и наблюдении у данного специалиста.</li>
                        <?php } ?>
                        <li>Консультация врача будет проведена в формате чат-общения в личном кабинете</li>
                        <li>Ожидание ответа врача до 24-х часов</li>
                        <li>Время консультации устанавливается на усмотрения врача и не может превышать 72 часов</li>
                        <li>Консультация будет завершена рекомендациями от специалиста</li>
                        <li><span class="text-danger">Внимание!</span> После оплаты, отменить консультацию невозможно
                        </li>
                    </ul>
                </div>
            </div>
            <hr>
            <div class="row">
                <div class="col-md-12" style="text-align: center;">
                    <?= Html::a('Оплатить консультацию', ['/consult/site/consult-pay'], ['class' => 'btn btn-primary consult-pay', 'data' => ['employee_id' => $model->id]]) ?>
                    <?= Html::button('Отмена', ['class' => 'btn btn-danger', 'data-dismiss' => 'modal']) ?>
                    <hr>
                    <?= Html::a('Договор оферты на платные услуги', ['/docs/okazuslug.pdf']) ?>
                </div>
            </div>
        </div>
    </div>

<?php
$this->registerJs('
$(document).on("click", ".consult-pay", function(e) {
    var employee_id = $(this).data("employee_id");
    var url = $(this).attr("href");
    
    $.ajax({
        data: { employee_id: employee_id },        
        method: "post",
        url: url     
    });
    
    e.preventDefault();    
});
');