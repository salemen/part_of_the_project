<?php
use yii\helpers\Html;
use app\models\consult\Consult;
use app\models\employee\Employee;

$user = Yii::$app->user;


?>

<?php if ($model) {
    foreach ($model as $value) {

        if ($value->advisors) { ?>
            <div class="row" style="position: relative;">
                <div class="col-md-3">
                    <?= Html::a(Html::tag('div', null, ['class' => 'bg-img-center', 'style' => 'background-image: url(' . Employee::getProfilePhoto($value) . '); height: 230px; width: 190px;']), ['doctor/view', 'id' => $value->id]) ?>
                </div>
                <div class="col-md-6">
                    <div class="info-list">
                        <h4 style="font-size: 24px; margin-top: 0px;"><?= Html::a($value->fullname, ['doctor/view', 'id' => $value->id]) ?></h4>
                        <p>
                            <?php
                            if (($cps = $value->customPositions) !== null) {
                                foreach ($cps as $cp) {
                                    echo Html::tag('span', $cp->value, ['style' => 'font-weight: 600;']) . '<br>';
                                }
                            }
                            if ($value->positionsDoctor) {
                                foreach ($value->positionsDoctor as $pos) {
                                    echo '<span style="font-weight: 600;">' . $pos->empl_pos . '</span><br>';
                                }
                                echo '<br>';
                            }
                            if ($value->degrees) {
                                foreach ($value->degrees as $degree) {
                                    echo '<span>' . $degree->empl_degree . '</span><br>';
                                }
                            }
                            if ($value->ranks) {
                                foreach ($value->ranks as $rank) {
                                    if ($rank->empl_rank) {
                                        echo '<span>' . $rank->empl_rank . '</span><br>';
                                    }
                                }
                            }
                            ?>
                        </p>
                    </div>
                </div>
                <div class="col-md-3" style="text-align: center;">
                    <?php
                    $cost = Consult::getConsultCost($value->advisors);
                    if ($cost) {
                        $cons = Consult::isConsultSecond($value->id) ? 'Стоимость повторной<br>онлайн-консультации' : 'Стоимость<br>онлайн-консультации';
                        $text = Html::tag('span', ($cost === 0) ? 'Бесплатно' : $cost . ' руб.', ['class' => 'text-danger', 'style' => 'font-size: 22px; font-weight: 600;']);
                        echo Html::tag('p', $cons . ':<br>' . $text, ['class' => 'price']);
                    }
                    if ($user->isGuest) {
                        echo Html::a('Записаться на <br>онлайн-консультацию', false, ['class' => 'btn btn-block btn-danger btn-login']);
                    } else {
                        if ($user->id !== $value->id) {
                            if (Consult::isConsultExist($value->id, $user->id)) {
                                echo Html::a('Начать консультацию', ['/consult'], ['class' => 'btn btn-block btn-success']);
                            } elseif($value->advisors->status == 10) {
                                echo Html::a('Записаться на <br>онлайн-консультацию', ['doctor/consult-details', 'id' => $value->id], ['class' => 'btn btn-block btn-danger btn-modal']);
                            } elseif($value->advisors->status == 0) {
                                echo Html::a('Записаться на <br>онлайн-консультацию', ['doctor/consult-none', 'id' => $value->id], ['class' => 'btn btn-block btn-danger btn-modal']);
                            }
                        }
                    }
                    echo $value->isScheduleExists() ? Html::a('Очная консультация', "https://330003.org/doctor/{$value->fullname}", ['class' => 'btn btn-block btn-warning', 'target' => '_blank']) : null;
                    echo Html::a('Подробнее о специалисте', ['doctor/view', 'id' => $value->id], ['class' => 'btn btn-block btn-primary']);
                    ?>
                </div>
            </div>
            <hr>
        <?php }
    }
} else {
    echo Html::tag('h4', 'Ничего не найдено. Измените критерии поиска и попробуйте снова');
} ?>