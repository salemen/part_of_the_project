<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\cardio\CardioDocs;

$this->title = 'Результат расшифровки ЭКГ №' . $model->cardio_id;
$this->params['breadcrumbs'][] = ['label'=>'Расшифровка ЭКГ', 'url'=>['index']];
$this->params['breadcrumbs'][] = ['label'=>'Заявка №' . $model->cardio_id, 'url'=>['view', 'id'=>$model->cardio_id]];
$this->params['breadcrumbs'][] = $this->title;

$disallowedAttrs = [
    'id',
    'cardio_id',
    'result',
    'created_at'
];

$norms = [
    'p_1'=>'не более 100 мс',
    'pq_1'=>'120 - 200 мс',
    'qrs_1'=>'60 - 100 мс',
    'qt_1'=>'до 400 с',
    'rr_1'=>'620 - 660 - 600 мс',
    'deg_1'=>'от +30 до +70 град',
    'chss_1'=>'60-90 уд/мин',            
    'eos_1'=>'',
    'rythm_1'=>'По рез-там диагностики'
];
?>

<div class="row">
    <div class="col-md-8">
        <?php $form = ActiveForm::begin([
            'id'=>'cardio-result-form',
            'enableAjaxValidation'=>true,
            'validateOnChange'=>true,
            'validateOnBlur'=>false
        ]) ?>

        <table class="table table-bordered">
            <tr>
                <th colspan="2" style="padding: 8px 0; text-align: center;">Показатели</th>
                <th style="padding: 8px 0; text-align: center;">Норма</th>
            </tr>
            <?php foreach ($model->attributes() as $label) { if (in_array($label, $disallowedAttrs)) { continue; } ?>
            <tr>
                <th style="padding: 8px 0; text-align: center;"><?= $model->getAttributeLabel($label) ?></th>
                <td style="padding: 0;"><?= $form->field($model, $label)->textInput(['maxlength'=>true])->error(false)->label(false) ?></td>
                <td style="padding: 8px 0; text-align: center;"><?= $norms[$label] ?></td>
            </tr>
            <?php } ?>
            <tr>
                <th style="padding: 8px 0; text-align: center;"><?= $model->getAttributeLabel('result') ?></th>
                <td colspan="2" style="padding: 0;"><?= $form->field($model, 'result')->textarea(['rows'=>3, 'style'=>'resize: vertical;'])->error(false)->label(false) ?></td>
            </tr>
        </table>

        <div class="form-group">
            <?= Html::submitButton('Сохранить', ['class'=>'btn btn-success']) ?>
            <?= Html::a('Отмена', ['view', 'id'=>$model->cardio_id], ['class'=>'btn btn-danger']) ?>
        </div>

        <?php ActiveForm::end() ?>
    </div>
    <div class="col-md-4">
        <?php if ($docs) { ?>
            <div class="row">
                <div class="col-md-12">
                    <h4>Снимки ЭКГ</h4>
                    <?php foreach ($docs as $doc) {
                        if ($doc->type == CardioDocs::TYPE_CURRENT) {
                            $file = '/uploads/' . $doc->file;
                            echo Html::tag('div', Html::a(Html::img($file, ['class'=>'img-responsive']), $file, ['class'=>'btn-magnific']), ['class'=>'col-md-3']);
                        }            
                    } ?>
                </div>
                <div class="col-md-12">
                    <h4>Снимки предыдущих ЭКГ</h4>
                    <?php foreach ($docs as $doc) {
                        if ($doc->type == CardioDocs::TYPE_PREVIOUS) {
                            $file = '/uploads/' . $doc->file;
                            echo Html::tag('div', Html::a(Html::img($file, ['class'=>'img-responsive']), $file, ['class'=>'btn-magnific']), ['class'=>'col-md-3']);
                        }            
                    } ?>
                </div>
            </div>        
        <?php } ?>
    </div>
</div>    

<?php
$this->registerCss('
.form-group { 
    margin-bottom: 0px;
}
.form-control { 
    border-color: transparent;
}
.table-bordered > thead > tr > th,
.table-bordered > tbody > tr > th,
.table-bordered > tfoot > tr > th,
.table-bordered > thead > tr > td,
.table-bordered > tbody > tr > td,
.table-bordered > tfoot > tr > td {
    border: 1px solid #dddddd;
}
');