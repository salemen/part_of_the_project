<?php
use yii\helpers\ArrayHelper;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;
use yii\helpers\Url;
use yii\web\JsExpression;
use app\models\geo\GeoCity;
use app\models\data\Department;
use kartik\date\DatePicker;
use app\models\OMS\Oms;
use kartik\depdrop\DepDrop;
use yii\widgets\MaskedInput;
?>

<style>
    .icheckbox_square-grey,.iradio_square-grey {
        border: 1px solid!important;
    }
</style>

    <div class="row">
        <div class="col-md-12">
            <?= $form->field($model, 'param1')->widget(DatePicker::className(), [
                    'value' => $model->param1,
                'pluginOptions'=>[
                    'autoclose'=>true,
                    'startDate'=>date("d.m.Y", time()),
                    'format'=>'dd.mm.yyyy'
                ]
            ])->label('Дата желаемого визита врача*',['class' => 'someClass']) ?>
        </div>
        <div class="col-md-4">
            <?php
            // получаем нужные города
            $city = GeoCity::find()->where(['IN', 'id', [322, 92, 84, 328, 102, 234, 70]])->all();
            // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
            $items = ArrayHelper::map($city,'id','name');
           ?>

            <?= $form->field($model, 'city')->dropDownList($items,['id'=>'cat-id', 'prompt' => 'Выберите город', 'onchange'=>'javascript:selectChanged()'])
                ->label('Город*',['class' => 'someClass'])?>

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

                    <?php
                    // получаем нужные поликлиники
                    $dep = Department::find()->where(['IN', 'id', [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23]])->all();
                    // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
                    $items = ArrayHelper::map($dep,'name','name');
                  ?>

                <?php    echo $form->field($model, 'clinic')->widget(DepDrop::classname(),[
                        'value'=>function($model) {
                            return $model->clinic;
                        },
                        'id'=>'select1',
                        'options'=>['id'=>'reg_id'],
                        'pluginOptions'=>[
                            'initialize' => true,
                            'depends'=>['cat-id'],
                            'initDepends'=>[$model->clinic],
                            'placeholder'=>'Выберите поликлинику',
                            'url'=>Url::to(['/cart/subcat'])
                    ]
                    ])->label('Моя поликлиника*',['class' => 'someClass']);

                ?>

                </div>
                <div class="col-md-4" id="step2">

                    <?php
                    // получаем нужные организации
                    $dep = Oms::find()->all();
                    // формируем массив, с ключем равным полю 'id' и значением равным полю 'name'
                    $items2 = ArrayHelper::map($dep,'oms','oms');
                    $params2 = [
                        'prompt' => 'Выберите организацию'
                    ]; ?>
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

                    <h3 id="mydiv" style="font-size: 17px;"></h3>

            </div>
        </div>

    </div>


<?php
$this->registerJs('
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

         $(document).ready(function(e) {
             $("#select1").change(function () {
                 var x = $('select option:selected').attr('name');
                 $('#step2').find('select').css('display','none');
                 $('#'+x).css('display','block');
             })
         });
</script>