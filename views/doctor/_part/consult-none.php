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
            <div class="col-md-12" style="padding-top: 15px">

                <center> <p>Врач занят. К сожалению онлайн-консультация в настоящее время недоступна</p></center>

            </div>
        </div>
        <hr>
    </div>
</div>

