<?php
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;
use app\models\vaccine\VacSickness;

$this->title = 'Вакцинация';
$this->params['breadcrumbs'][] = $this->title;
$this->params['hide-footer'][] = true;
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <?php $form = ActiveForm::begin() ?>
            
            <div class="row">
                <div class="col-md-10">
                    <?= $form->field($model, 'fullname')->textInput(['maxlength'=>true])->label('ФИО') ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'user_birth')->widget(MaskedInput::className(), [
                        'mask'=>'99.99.9999',
                        'options'=>[
                            'class'=>'form-control'
                        ]
                    ])->label('Дата рождения') ?>
                </div>
            </div>

            <?= $form->field($model, 'city')->widget(Select2::className(), [
                'initValueText'=>($model->city) ? $model->city : null,
                'pluginOptions'=>[
                    'ajax'=>[
                        'data'=>new JsExpression('function(params) { return {query: params.term}; }'),
                        'dataType'=>'json',
                        'delay'=>250,
                        'url'=>Url::to(['/data/city', 'keytext'=>true])
                    ],
                    'minimumInputLength'=>3,
                    'placeholder'=>'Укажите город',
                    'templateResult'=>new JsExpression('function(data) { return data.text; }'),
                    'templateSelection'=>new JsExpression('function (data) { return data.text; }')
                ]
            ])->label('Город') ?>

            <?= $form->field($model, 'sicks')->widget(Select2::className(), [
                'data'=>ArrayHelper::map(VacSickness::find()->orderBy('name')->all(), 'id', 'name'),
                'pluginOptions'=>[
                    'multiple'=>true,
                    'placeholder'=>'Укажите болезни, которыми вы переболели в ближайший год'
                ]
            ])->label('Болезни') ?>

            <div class="form-group">
                <?= Html::submitButton('Сформировать календарь вакцинации', ['class'=>'btn btn-primary']) ?>
            </div>

            <?php ActiveForm::end() ?>           
        </div>
    </div>
</div>