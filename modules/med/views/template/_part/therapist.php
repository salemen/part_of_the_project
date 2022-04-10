<?php
use kartik\date\DatePicker;

$values = $model->getValues();
?>

<?= $form->field($model, 'сomplaints')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>
<?= $form->field($model, 'anamnez')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>

<div class="row">
    <?= $form->field($model, 'health', ['options'=>['class'=>'col-md-6']])->dropDownList($values['health'], ['prompt'=>'Выберите нужное', 'value'=>($model->health) ? $model->getValue('health', $model->health) : null]) ?>
    <?= $form->field($model, 'self', ['options'=>['class'=>'col-md-6']])->dropDownList($values['self'], ['prompt'=>'Выберите нужное', 'value'=>($model->self) ? $model->getValue('self', $model->self) : null]) ?>
</div>
<div class="row">
    <?= $form->field($model, 'night', ['options'=>['class'=>'col-md-4']])->dropDownList($values['night'], ['prompt'=>'Выберите нужное', 'value'=>($model->night) ? $model->getValue('night', $model->night) : null]) ?>
    <?= $form->field($model, 'appetite', ['options'=>['class'=>'col-md-4']])->dropDownList($values['appetite'], ['prompt'=>'Выберите нужное', 'value'=>($model->appetite) ? $model->getValue('appetite', $model->appetite) : null]) ?>
    <?= $form->field($model, 'skin', ['options'=>['class'=>'col-md-4']])->dropDownList($values['skin'], ['prompt'=>'Выберите нужное', 'value'=>($model->skin) ? $model->getValue('skin', $model->skin) : null]) ?>
</div>
<div class="row">
    <?= $form->field($model, 'lu', ['options'=>['class'=>'col-md-4']])->dropDownList($values['lu'], ['prompt'=>'Выберите нужное', 'value'=>($model->lu) ? $model->getValue('lu', $model->lu) : null]) ?>
    <?= $form->field($model, 'lulen', ['options'=>['class'=>'col-md-4']])->textInput(['maxlength'=>true]) ?>
    <?= $form->field($model, 'lutype', ['options'=>['class'=>'col-md-4']])->dropDownList($values['lutype'], ['prompt'=>'Выберите нужное', 'value'=>($model->lutype) ? $model->getValue('lutype', $model->lutype) : null]) ?>
</div>
<div class="row">
    <?= $form->field($model, 'zeva', ['options'=>['class'=>'col-md-3']])->dropDownList($values['zeva'], ['prompt'=>'Выберите нужное', 'value'=>($model->zeva) ? $model->getValue('zeva', $model->zeva) : null]) ?>
    <?= $form->field($model, 'mindalini', ['options'=>['class'=>'col-md-3']])->dropDownList($values['mindalini'], ['prompt'=>'Выберите нужное', 'value'=>($model->mindalini) ? $model->getValue('mindalini', $model->mindalini) : null]) ?>
    <?= $form->field($model, 'nalet', ['options'=>['class'=>'col-md-6']])->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>
</div>
<div class="row">
    <?= $form->field($model, 'nbreath', ['options'=>['class'=>'col-md-4']])->dropDownList($values['nbreath'], ['prompt'=>'Выберите нужное', 'value'=>($model->nbreath) ? $model->getValue('nbreath', $model->nbreath) : null]) ?>
    <?= $form->field($model, 'nbreathval', ['options'=>['class'=>'col-md-4']])->dropDownList($values['nbreathval'], ['prompt'=>'Выберите нужное', 'value'=>($model->nbreathval) ? $model->getValue('nbreathval', $model->nbreathval) : null]) ?>
    <?= $form->field($model, 'nbreathtype', ['options'=>['class'=>'col-md-4']])->dropDownList($values['nbreathtype'], ['prompt'=>'Выберите нужное', 'value'=>($model->nbreathtype) ? $model->getValue('nbreathtype', $model->nbreathtype) : null]) ?>
</div>
<div class="row">
    <?= $form->field($model, 'lbreath', ['options'=>['class'=>'col-md-3']])->dropDownList($values['lbreath'], ['prompt'=>'Выберите нужное', 'value'=>($model->lbreath) ? $model->getValue('lbreath', $model->lbreath) : null]) ?>
    <?= $form->field($model, 'hripi', ['options'=>['class'=>'col-md-3']])->dropDownList($values['hripi'], ['prompt'=>'Выберите нужное', 'value'=>($model->hripi) ? $model->getValue('hripi', $model->hripi) : null]) ?>
    <?= $form->field($model, 'hripiloc', ['options'=>['class'=>'col-md-6']])->textarea(['class'=>'form-control']) ?>
</div>
<div class="row">
    <?= $form->field($model, 'theart', ['options'=>['class'=>'col-md-3']])->dropDownList($values['theart'], ['prompt'=>'Выберите нужное', 'value'=>($model->theart) ? $model->getValue('theart', $model->theart) : null]) ?>
    <?= $form->field($model, 'pulse', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength'=>true]) ?>
    <?= $form->field($model, 'pulseritme', ['options'=>['class'=>'col-md-3']])->dropDownList($values['pulseritme'], ['prompt'=>'Выберите нужное', 'value'=>($model->pulseritme) ? $model->getValue('pulseritme', $model->pulseritme) : null]) ?>
    <?= $form->field($model, 'tongue', ['options'=>['class'=>'col-md-3']])->dropDownList($values['tongue'], ['prompt'=>'Выберите нужное', 'value'=>($model->tongue) ? $model->getValue('tongue', $model->tongue) : null]) ?>
</div>
<hr>
<div class="row">
    <?= $form->field($model, 'stomach', ['options'=>['class'=>'col-md-4']])->dropDownList($values['stomach'], ['prompt'=>'Выберите нужное', 'value'=>($model->stomach) ? $model->getValue('stomach', $model->stomach) : null]) ?>
    <?= $form->field($model, 'onpulp', ['options'=>['class'=>'col-md-4']])->dropDownList($values['onpulp'], ['prompt'=>'Выберите нужное', 'value'=>($model->onpulp) ? $model->getValue('onpulp', $model->onpulp) : null]) ?>
    <?= $form->field($model, 'morbidity', ['options'=>['class'=>'col-md-4']])->dropDownList($values['morbidity'], ['prompt'=>'Выберите нужное', 'value'=>($model->morbidity) ? $model->getValue('morbidity', $model->morbidity) : null]) ?>
</div>

<?= $form->field($model, 'othermorbidity')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>
<hr>

<div class="row">
    <?= $form->field($model, 'liver', ['options'=>['class'=>'col-md-3']])->dropDownList($values['liver'], ['prompt'=>'Выберите нужное', 'value'=>($model->liver) ? $model->getValue('liver', $model->liver) : null]) ?>
    <?= $form->field($model, 'liverlen', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength'=>true]) ?>
    <?= $form->field($model, 'sidesolidity', ['options'=>['class'=>'col-md-3']])->dropDownList($values['sidesolidity'], ['prompt'=>'Выберите нужное', 'value'=>($model->sidesolidity) ? $model->getValue('sidesolidity', $model->sidesolidity) : null]) ?>
    <?= $form->field($model, 'sidepain', ['options'=>['class'=>'col-md-3']])->dropDownList($values['sidepain'], ['prompt'=>'Выберите нужное', 'value'=>($model->sidepain) ? $model->getValue('sidepain', $model->sidepain) : null]) ?>
</div>

<?= $form->field($model, 'bowel')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'swelling')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'otherdata')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>

<?= $form->field($model, 'diagnosis')->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>

<div class="row">
    <?= $form->field($model, 'recommendation', ['options'=>['class'=>'col-md-6']])->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>
    <?= $form->field($model, 'exploration', ['options'=>['class'=>'col-md-6']])->textarea(['class'=>'form-control', 'style'=>'resize: vertical;']) ?>
</div>

<div class="row">
    <?= $form->field($model, 'docnum', ['options'=>['class'=>'col-md-3']])->textInput(['maxlength'=>true]) ?>
    <?= $form->field($model, 'docnum_from', ['options'=>['class'=>'col-md-3']])->widget(DatePicker::className(), [
        'pluginOptions'=>[
          'autoclose'=>true,
          'format'=>'dd.mm.yyyy'
        ]
    ]) ?>
    <?= $form->field($model, 'docnum_to', ['options'=>['class'=>'col-md-3']])->widget(DatePicker::className(), [
        'pluginOptions'=>[
          'autoclose'=>true,
          'format'=>'dd.mm.yyyy'
        ]
    ]) ?>
    <?= $form->field($model, 'nextvisit', ['options'=>['class'=>'col-md-3']])->widget(DatePicker::className(), [
        'pluginOptions'=>[
          'autoclose'=>true,
          'format'=>'dd.mm.yyyy'
        ]
    ]) ?>
</div>