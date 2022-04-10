<?php
use yii\helpers\Html;
use app\models\consult\Consult;
use app\models\employee\Employee;

$user = Yii::$app->user;
?>
<style>
    input {
        display: block;
        text-align: left;
    }
    input:-moz-placeholder {
        color: #999999;
        text-align: center;
    }
    input::-moz-placeholder {
        color: #999999;
        text-align: center;
    }
    input:-ms-input-placeholder {
        color: #999999;
        text-align: center;
    }
    input::-webkit-input-placeholder {
        color: #999999;
        text-align: center;
    }
   
</style>
<div class="logotip">
 <div id="bg_popup1" >
        <div id="popup1" class="pop-up">
                <?= Html::img('@web/img/pap.png', ['alt' => 'Окно помощи 0323']) ?>
               <form method="get" action="/doctorreg/form">
                <div class="fameli">
                    <input required placeholder="Фамилия Имя" class="fio1" type="text" name="fio" style="width:310px; border: 1px;">
                </div>
                <div class="telephone">
                    <input required placeholder="+7 --- -- -- --" class="tel1" type="number" name="tel" style="width:310px; border: 1px;">
                </div>
                <div class="sub1">
                    <input class="tel btn btn-danger btn-block btn-submit btn-submit2" type="submit" name="submit1" style="width:290px; border-radius: 5px;" value="Заказать звонок">
                </div>

                <p class="ch1" ><input type="checkbox" checked="checked" name="technologies[]" value="" />
                    <span class="letter1">согласен(а) на обработку персональных данных</span> </p>
                   <a class="closee1" href="#" title="Закрыть" onclick="document.getElementById('bg_popup1').style.display='none'; return false;">X</a>
                   <a class="closeesps1" href="#" title="Закрыть" onclick="document.getElementById('bg_popup1').style.display='none'; return false;">
                       <span class="letter1-close"><u>Спасибо, в следующий раз</u></span></a>
               </form>
            </div>
        </div>
 </div>
<div class="logotip-min">
    <div id="bg_popup1-min" >
        <div id="popup1-min">
            <?= Html::img('@web/img/pap.png', ['alt' => 'Окно помощи 0323']) ?>
            <form method="get" action="/doctorreg/form">
                <div class="fameli1-min">
                    <input required placeholder="Фамилия Имя" class="fio1" type="text" name="fio" style="width:270px; border: 1px;">
                </div>
                <div class="telephone1-min">
                    <input required placeholder="+7 --- -- -- --" class="tel1" type="number" name="tel" style="width:270px; border: 1px;">
                </div>
                <div class="sub1-min">
                    <input class="tel btn btn-danger btn-block btn-submit btn-submit2" type="submit" name="submit1" style="width:260px; border-radius: 5px;" value="Заказать звонок">
                </div>

                <p class="ch1-min" ><input type="checkbox" checked="checked" name="technologies[]" value="" />
                    <span class="letter1-min">согласен(а) на обработку персональных данных</span> </p>
                <a class="closee1" href="#" title="Закрыть" onclick="document.getElementById('bg_popup1-min').style.display='none'; return false;">X</a>

            </form>
        </div>
    </div>
</div>

<?php if ($model) {
    foreach ($model as $value) {
        if ($value->advisor) { ?>
            <div class="row" style="position: relative;">
                <div class="col-md-3">
                    <?= Html::a(Html::tag('div', null, ['class' => 'bg-img-center', 'style' => 'background-image: url(' . Employee::getProfilePhoto($value) . '); height: 230px; width: 190px;']), ['view', 'id' => $value->id]) ?>
                </div>
                <div class="col-md-6">
                    <div class="info-list">
                        <h4 style="font-size: 24px; margin-top: 0px;"><?= Html::a($value->fullname, ['view', 'id' => $value->id]) ?></h4>
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
                    $text = Html::tag('span', Yii::$app->params['specialConsult']['cost'] . ' руб.', ['class' => 'text-danger', 'style' => 'font-size: 22px; font-weight: 600;']);
                    echo Html::tag('p', 'Стоимость консультации' . ':<br>' . $text, ['class' => 'price']);
                    if ($user->isGuest) {
                        echo Html::a('Записаться на <br>онлайн-консультацию', false, ['class' => 'btn btn-block btn-danger btn-login']);
                    } else {
                        if ($user->id !== $value->id) {
                            if (Consult::isConsultExist($value->id, $user->id, true)) {
                                echo Html::a('Начать консультацию', ['/consult'], ['class' => 'btn btn-block btn-success']);
                            } else {
                                echo Html::a('Записаться на <br>онлайн-консультацию', ['consult-details', 'id' => $value->id], ['class' => 'btn btn-block btn-danger btn-modal']);
                            }
                        }
                    }
                    echo Html::a('Подробнее о<br> специалисте', ['view', 'id' => $value->id], ['class' => 'btn btn-block btn-primary']);
                    ?>
                </div>
            </div>
            <hr>
        <?php }
    }
} else {
    echo Html::tag('h4', 'Ничего не найдено. Измените критерии поиска и попробуйте снова');
} ?>

<script type="text/javascript">
    var delay_popup = 7000;
    setTimeout("document.getElementById('bg_popup1').style.display='block'", delay_popup);
    var delay_popup = 7000;
    setTimeout("document.getElementById('bg_popup1-min').style.display='block'", delay_popup);
</script>
<?php
$this->registerJs('
iCheckInit();
$(".btn-submit").prop("disabled", false);
$("input").on("ifChecked", function(event) {
    $(".btn-submit ").prop("disabled", false);
});
$("input").on("ifUnchecked", function(event) {
    $(".btn-submit").prop("disabled", true);
});

iCheckInit();
$(".btn-submit2").prop("disabled", false);
$("input").on("ifChecked", function(event) {
    $(".btn-submit2").prop("disabled", false);
});
$("input").on("ifUnchecked", function(event) {
    $(".btn-submit2").prop("disabled", true);
});
');?>

<script type="text/javascript">
    const popup = document.querySelector('.pop-up');

    document.onclick = function(e){
        if ( event.target.className != 'pop-up' ) {
            setTimeout(function(){
                $('.pop-up').removeClass('pop-up').addClass('pop-style');
            },200)
            setTimeout(function(){
                $('.pop-style').removeClass('pop-style').addClass('pop-up');
            },2500)
        };
    };
</script>
