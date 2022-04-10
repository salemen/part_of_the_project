<?php

use yii\helpers\ArrayHelper;
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use kartik\date\DatePicker;
use app\models\data\Department;
use app\models\oms\Oms;
use kartik\depdrop\DepDrop;
use yii\web\JsExpression;


$this->title = $title;
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Заявки от посетителей Онлайн-Поликлиники'
], 'description');

$user = Yii::$app->user;
?>

<style>
    .icheckbox_square-grey,.iradio_square-grey {
        border: 1px solid!important;
    }
</style>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin([
            'id'=>'proposal-form'
        ]) ?>

        <h1 class="vrach-header">Вызов врача на дом <a href="#" onclick="openbox('boxv'); return false">?</a></h1>


        <div id="boxv" style="display: none;">
            <h5 style="font-size:16px; max-width: 1050px;"> Вызов врача на дом - онлайн-сервис, который поможет вам оперативно вызвать медицинского специалиста Группы компании ЦСМ-Санталь на дом для:
                <br> - Консультации,<br>
                - Ультразвукового исследования,
                <br> - Процедур и манипуляций"
                <br> Вам больше не нужно ждать своей очереди на телефоне.
                Просто внесите все нужные данные, подтвердите согласие на обработку персональных данных, и с вами свяжутся в ближайшее время.
            </h5>
        </div>

        <ul class="timeline">
            <li>
                <i class="fa fa-site-font bg-blue">1</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Заполните персональные данные</h2>
                    <div class="timeline-body">
                        <?= ($user->isGuest) ? Html::tag('p', 'Если у вас уже есть учетная запись, пожалуйста ' . Html::a('авторизуйтесь', Url::current(), ['class'=>'btn-login text-danger'])) : null ?>
                        <div class="row">   
                            <div class="col-md-4" style="margin-bottom: 15px">
                                <?= $form->field($model, 'user_f')->textInput(['class'=>'form-control' ])->label('Фамилия*',['class' => 'someClass']) ?>
                            </div>
                            <div class="col-md-4" style="margin-bottom: 15px">
                                <?= $form->field($model, 'user_i')->textInput(['class'=>'form-control'])->label('Имя*',['class' => 'someClass']) ?>
                            </div>
                            <div class="col-md-4" style="margin-bottom: 15px">
                                <?= $form->field($model, 'user_o')->textInput(['class'=>'form-control'])->label('Отчество',['class' => 'someClass']) ?>
                            </div>
                            <div class="col-md-2" style="margin-top: 5px">

                                <?= $form->field($model, 'user_birth')->widget(DatePicker::className(), [
                                    'name' => 'dp_1',
                                   'value'=>function($model) {
                                        return $model->user_birth;
                                    },
                                    'type' => DatePicker::TYPE_INPUT,
                                    'pluginOptions'=>[
                                        'autoclose'=>true,
                                        'endDate'=> date("d.m.Y") > date("d.m.Y")  ? date("d.m.Y",microtime(true)-(60*60*24)) : date("d.m.Y"),
                                        'format'=>'dd.mm.yyyy',
                                        'orientation' => 'bottom',
                                    ]
                                ])->label('Дата рождения*',['class' => 'someClass']) ?>

                            </div>
                            <div class="col-md-2" style="margin-top: 5px">
                                <?= $form->field($model, 'sex')->widget(Select2::className(), [
                                    'data'=>[1=>'Мужской', 0=>'Женский'],
                                    'options'=>[
                                        'class'=>'form-control',
                                        'placeholder'=> 'Выберите пол'
                                    ]
                                ])->label('Пол*',['class' => 'someClass']) ?>
                            </div>
                            <div class="col-md-4" style="margin-top: 5px">
                                <?= $form->field($model, 'email')->textInput(['class'=>'form-control'])
                                    ->label('Эл.почта*',['class' => 'someClass'])?>
                            </div>
                            <div class="col-md-4" style="margin-top: 5px">
                                <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
                                    'jsOptions'=>[
                                        'preferredCountries'=>['ru']
                                    ]
                                ])->label('Телефон*',['class' => 'someClass']) ?>
                            </div>                           
                        </div>
                    </div>
                </div>
            </li>   
            <li>
                <i class="fa fa-site-font bg-yellow">2</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Заполните необходимые параметры</h2>
                    <div class="timeline-body">
                        <div class="row">
                            <div class="col-md-12">
                                <?= $form->field($model, 'param1')->widget(DatePicker::className(), [
                                    'value' => $model->param1,
                                    'pluginOptions'=>[
                                        'autoclose'=>true,
                                        'startDate'=> date("H") >= 19 ? date("d.m.Y",microtime(true)+(60*60*24)) : date("d.m.Y"),
                                        'format'=>'dd.mm.yyyy'
                                    ]
                                ])->label('Дата желаемого визита врача*',['class' => 'someClass']) ?>
                            </div>
                            <div class="col-md-4">

                                <?php if(isset($model->city)){
                                    echo $form->field($model, 'city')->textInput($items,['id'=>'cat-id', 'prompt' => 'Выберите город', 'onchange'=>'javascript:selectChanged()'])
                                    ->label('Город*',['class' => 'someClass']);
                                }else{
                                  echo  $form->field($model, 'city')->dropDownList($items,['id'=>'cat-id', 'prompt' => 'Выберите город', 'onchange'=>'javascript:selectChanged()'])
                                    ->label('Город*',['class' => 'someClass']);
                                }
                                ?>

                            </div>
                            <div class="col-md-8">
                                <?= $form->field($model, 'address')->textInput(['class'=>'form-control'])->label('Адрес*',['class' => 'someClass']) ?>
                            </div>
                            <div class="col-md-12">
                                <?= $form->field($model, 'comment')->textarea(['class'=>'form-control', 'rows'=>4, 'style'=>'resize: vertical;']) ?>
                            </div>
                            <div class="col-md-12">
                                <div class="col-md-12" style="padding-left: 5px; padding-right: 5px;">
                                    <?= $form->field($model, 'polis_exists')->checkbox(['id'=>'polis_exists','checked'=>false])
                                        ->label('Вы прикреплены к медицинской организации ЦСМ-Санталь? &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Да &nbsp;',['class' => 'someClass']) ?>
                                </div>
                                <div id="polis_attributes" class="row">
                                    <div class="col-md-4">

                                        <?php if(isset($model->clinic)){
                                            echo $form->field($model, 'clinic')->textInput()->label('Моя поликлиника*',['class' => 'someClass']);
                                        }else{
                                            echo $form->field($model, 'clinic')->widget(DepDrop::classname(),[
                                                'value'=>function($model) {
                                                    return $model->clinic;
                                                },
                                                'options'=>['id'=>'reg_id'],
                                                'pluginOptions'=>[
                                                    'depends'=>['cat-id'],
                                                    'initDepends'=>[$model->clinic],
                                                    'placeholder'=>'Выберите поликлинику',
                                                    'url'=>Url::to(['/cart/subcat'])
                                                ]
                                            ])->label('Моя поликлиника*',['class' => 'someClass']);
                                        }
                                       ?>

                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'polis_oms_org')->dropDownList($items2)
                                            ->label('Страховая компания ОМС*',['class' => 'someClass'])?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'polis_oms_number')->widget(MaskedInput::className(), [
                                            'id'=>'polis_oms_number',
                                            'mask'=>'9999999999999999',
                                            'options'=>[
                                                'class'=>'form-control'
                                            ]
                                        ]) ?>

                                    </div>
                                </div>

                                <div id="price" class="timeline-item">

                                    <h2 style="color: #193e85;
                            font-size: 22px;
                            font-weight: 600;">
                                        Стоимость наших услуг</h2>
                                    <?php if(!isset($model->city)){ ?>
                                       <h3 id="mydiv" style="font-size: 17px;"></h3>
                                    <?php } elseif ($model->city == "Томск"){?>
                                         <a href="https://0370.ru/price/" target="_blank">в г.Томск (узнать стоимость)</a><br>
                                    <?php } elseif ($model->city == "Краснодар") { ?>
                                        <a href="https://santal-krasnodar.ru/price/" target="_blank"> в г.Краснодар (узнать стоимость) </a><br>
                                    <?php } elseif ($model->city == "Новосибирск") { ?>
                                        <a href="https://santal-novosibirsk.ru/price/" target="_blank">в г.Новосибирск (узнать стоимость)</a><br>
                                    <?php } elseif ($model->city == "Кызыл") { ?>
                                        <a href="https://santal-tyva.ru/price/" target="_blank">в г.Кызыл (узнать стоимость)</a><br>
                                    <?php } elseif ($model->city == "Геленджик") { ?>
                                        <a href="https://santal-gelendzhik.ru/price/" target="_blank">в г.Геленджик (узнать стоимость)</a>
                                     <?php  } ?>

                                </div>
                            </div>

                        </div>

                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-site-font bg-green">3</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Согласие на обработку <?= Html::a('персональных данных', ['/info/datagree'], ['style'=>'text-decoration: underline;', 'target'=>'_blank']) ?></h2>
                    <div class="timeline-body">
                        <div class="timeline-footer">
                            <?= Html::checkbox('checkbox-agree', false, ['id'=>'checkbox-agree', 'label'=>'Я согласен(а) на обработку персональных данных']) ?>
                            <hr>
                            <?= Html::submitButton('Отправить заявку', ['class'=>'btn btn-primary btn-submit']) ?>
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <div class="timeline-item">
                    <h2 class="timeline-header">Связаться с нами</h2>
                    <div class="timeline-body">
                        <h3 style="font-size: 13px;">
                           Томск <a href="tel:+7 (3822) 90-03-03">+7 (3822) 90-03-03</a></br>
                       Краснодар <a href="tel:+7 (861) 205-10-03">+7 (861) 205-10-03</a></br>
                        Новосибирск<a href="tel:+7 (383) 318-12-90">+7 (383) 318-12-90</a></br>
                        Геленджик<a href="tel:+7 (861-41) 515-47">+7 (861-41) 515-47</a></br>
                        Геленджик<a href="tel:+7 (988) 521-03-33">+7 (988) 521-03-33</a></br>
                        Адыгея<a href="tel:+7 (87772) 200-22">+7 (87772) 200-22</a></br>
                        Адыгея<a href="tel:+7 (918) 960-9839">+7 (918) 960-9839</a></br>
                        Юрга<a href="tel:+7 (38451)77-333">+7 (38451)77-333</a></br>
                        </h3>
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-clock-o bg-gray" style="line-height: 50px;"></i>
            </li>
        </ul>
        <?php ActiveForm::end() ?>     
    </div>
</div>

<?php
$this->registerJs('
iCheckInit();
$(".btn-submit").prop("disabled", true);
$("#checkbox-agree").on({
    ifChecked: function() {
        $(".btn-submit").prop("disabled", false);
        
    },
    ifUnchecked: function() {
        $(".btn-submit").prop("disabled", true);
    }
});

togglePolis($("#polis_exists").is(":checked") ? true : false);
$("#polis_exists").on({
    ifChecked: function() {
        togglePolis(true);
       togglePolis1(false);
    },
    ifUnchecked: function() {
        togglePolis(false);
        togglePolis1(true);
    }
});


function togglePolis(value) {
    $("#polis_oms_org").prop("required", value);
    $("#polis_oms_number").prop("required", value);
    $("#polis_attributes").css("display", (value ? "block" : "none"));
}


function togglePolis1(value) {
    $("#address").prop("required", value);
    $("#price").css("display", (value ? "block" : "none"));
}


');?>

<script type="text/javascript">
    function selectChanged() {
        var sel = document.getElementById('cat-id');
        var str = sel.selectedIndex ? (sel.options[sel.selectedIndex].innerHTML + '') : '___';
        if ( str === 'Краснодар') {
            document.getElementById('mydiv').innerHTML = '<a href="https://santal-krasnodar.ru/price/" target="_blank"> в г.Краснодар (узнать стоимость) </a>';
        }else if ( str === 'Томск') {
            document.getElementById('mydiv').innerHTML = '<a href="https://0370.ru/price/" target="_blank">в г.Томск (узнать стоимость)</a>';
        }else if ( str === 'Новосибирск') {
            document.getElementById('mydiv').innerHTML = '<a href="https://santal-novosibirsk.ru/price/" target="_blank">в г.Новосибирск (узнать стоимость)</a>';
        }else if ( str === 'Кызыл') {
            document.getElementById('mydiv').innerHTML = '<a href="https://santal-tyva.ru/price/" target="_blank">в г.Кызыл (узнать стоимость)</a>';
        }else if ( str === 'Геленджик'){
            document.getElementById('mydiv').innerHTML = '<a href="https://santal-gelendzhik.ru/price/" target="_blank">в г.Геленджик (узнать стоимость)</a>';
        }else
            document.getElementById('mydiv').innerHTML = '&#8213;'
    }


    function openbox(id){
        display = document.getElementById(id).style.display;

        if(display=='none'){
            document.getElementById(id).style.display='block';
        }else{
            document.getElementById(id).style.display='none';
        }
    }
</script>


