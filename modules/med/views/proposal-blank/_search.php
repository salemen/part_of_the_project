<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;

$isSearch = isset(Yii::$app->request->queryParams['search']);
?>

<?= Html::a('Добавить', ['new-create'], ['class'=>'btn btn-success', 'style'=>'margin-right:10px']) ?>
<?= Html::a('Поиск', '#collapseOne', ['class'=>'btn btn-primary collapsed', 'data'=>['parent'=>'#accordion', 'toggle'=>'collapse'], 'style'=>'margin-right: 3px;']) ?>
<div id="accordion" style="margin-top: 10px;">
    <div id="collapseOne" class="panel-collapse collapse">
        <?php $form = ActiveForm::begin(['action'=>['index'], 'method'=>'get']) ?>

        <div class="row">
            <div class="col-md-4">
                <?= $form->field($model, 'city')->textInput(['placeholder'=>'Город'])->label(false) ?>
            </div>

            <div class="col-md-4">
                <?= $form->field($model, 'created_at')->widget(DatePicker::className(), [
                    'name' => 'dp_1',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [
                        'placeholder' => 'Дата добавления от'
                    ],
                    'pluginOptions'=>[
                        'autoclose'=>true,
                        'format'=>'dd.mm.yyyy',
                        'orientation' => 'bottom',
                    ]
                ])->label(false)?>

            </div>
            <div class="col-md-4">
                <?= $form->field($model, 'updated_at')->widget(DatePicker::className(), [
                    'name' => 'dp_1',
                    'type' => DatePicker::TYPE_INPUT,
                    'options' => [
                        'placeholder' => 'Дата добавления до'
                    ],
                    'pluginOptions'=>[
                        'autoclose'=>true,
                        'format'=>'dd.mm.yyyy',
                        'orientation' => 'bottom',
                    ]
                ])->label(false)?>

            </div>

        </div>

        <div class="form-group">
            <?= Html::submitButton('Найти', ['class'=>'btn btn-sm btn-primary', 'style'=>'margin-right:10px']) ?>
            <?=  Html::a('Очистить поиск ', ['index'], ['class'=>'btn btn-sm btn-default']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>

</div>