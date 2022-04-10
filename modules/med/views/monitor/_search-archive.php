<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$isSearch = isset(Yii::$app->request->queryParams['search']);
?>

<?= Html::a('Поиск', '#collapseOne', ['class'=>'btn btn-sm btn-primary collapsed', 'data'=>['parent'=>'#accordion', 'toggle'=>'collapse'], 'style'=>'margin-right: 3px;']) ?>
<?= ($isSearch) ? Html::a('Очистить фильтр', ['index-archive'], ['class'=>'btn btn-sm btn-danger']) : null ?>
<div id="accordion" style="margin-top: 10px;">
    <div id="collapseOne" class="panel-collapse collapse">
        <?php $form = ActiveForm::begin(['action'=>['index-archive'], 'method'=>'get']) ?>

        <?= $form->field($model, 'search')->textInput(['placeholder'=>'Параметры поиска (ФИО, Номер телефона, Город, Адрес)'])->label(false) ?>

        <?= $form->field($model, 'protocol_type')->widget(Select2::className(), [
            'data'=>[
                10=>'ОРВИ/COVID-19',
                20=>'Ветряная оспа',
                30=>'Коклюш',
                40=>'Сахарный диабет',
                50=>'Гипертоническая болезнь',
                60=>'Бронхиальная астма',
                70=>'Реабилитация'
            ],
            'options'=>[
                'placeholder'=>'Тип протокола'
            ]
        ])->label(false) ?>

        <?= $form->field($model, 'reason')->widget(Select2::className(), [
            'data'=>[
                10=>'Добровольно. Не был(а) за границей. Не контактировал(а) с лицами, имеющими признаки респ.заболеваний и/или вернулись из других стран (в последние 14 дней)',
                20=>'Был(а) за границей (в последние 14 дней)',
                30=>'Контактировал(а) (в последние 14 дней) с лицами, имеющими признаки респ.заболеваний и/или вернулись из других стран',
                40=>'Контактировал(а) (в последние 14 дней) с лицами, у которых подтвержден диагноз коронавирусной инфекции'
            ],
            'options'=>[
                'placeholder'=>'Причины постановки на учет'
            ]
        ])->label(false) ?>

        <div class="form-group">
            <?= Html::submitButton('Применить', ['class'=>'btn btn-sm btn-primary']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
</div>