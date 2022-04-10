<?php
use borales\extensions\phoneInput\PhoneInput;
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\MaskedInput;

$this->title = 'Расшифровка ЭКГ Онлайн';
$this->params['breadcrumbs'][] = $this->title;
$this->registerMetaTag([
    'name'=>'description',
    'content'=>'Мы дистанционно расшифруем присланную Вами ЭКГ'
], 'description');

$user = Yii::$app->user->identity;
?>

<div class="row">
    <div class="col-md-12">
        <?php $form = ActiveForm::begin([
            'id'=>'cardio-form',
            'options'=>[
                'enctype'=>'multipart/form-data'
            ]
        ]) ?>
        <ul class="timeline">
            <li>
                <i class="fa fa-site-font bg-blue">1</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Заполните персональные данные</h2>
                    <div class="timeline-body">
                        <?= ($user == null) ? Html::tag('p', 'Если у вас уже есть учетная запись, пожалуйста ' . Html::a('авторизуйтесь', '#', ['class'=>'btn-login text-danger'])) : null ?>
                        <div class="row">                            
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'user_f')->textInput(['class'=>'form-control', 'placeholder'=>'Фамилия'])->label(false)->error(false) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'user_i')->textInput(['class'=>'form-control', 'placeholder'=>'Имя'])->label(false)->error(false) ?>
                                    </div>
                                    <div class="col-md-4">
                                        <?= $form->field($model, 'user_o')->textInput(['class'=>'form-control', 'placeholder'=>'Отчество'])->label(false)->error(false) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'user_birth')->widget(MaskedInput::className(), [
                                            'mask'=>'99.99.9999',
                                            'options'=>[
                                                'class'=>'form-control',
                                                'placeholder'=>'Дата рождения'
                                            ]
                                        ])->label(false)->error(false) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'sex')->widget(Select2::className(), [
                                            'data'=>[1=>'Мужской', 0=>'Женский'],
                                            'options'=>[
                                                'class'=>'form-control',
                                                'placeholder'=>'Пол',
                                            ]
                                        ])->label(false)->error(false) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'patient_height')->textInput(['placeholder'=>'Рост (см)'])->label(false)->error(false) ?>
                                    </div>
                                    <div class="col-md-3">
                                        <?= $form->field($model, 'patient_weight')->textInput(['placeholder'=>'Вес (кг)'])->label(false)->error(false) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'email')->textInput(['class'=>'form-control', 'placeholder'=>'E-mail'])->label(false)->error(false) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'phone')->widget(PhoneInput::className(), [
                                            'jsOptions'=>[
                                                'preferredCountries'=>['ru']
                                            ]
                                        ])->label(false)->error(false) ?> 
                                    </div>
                                </div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </li>   
            <li>
                <i class="fa fa-site-font bg-yellow">2</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Загрузите фото ЭКГ</h2>
                    <div class="timeline-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-6">
                                        <?= $form->field($model, 'ekg_current[]')->fileInput(['multiple'=>true]) ?>     
                                        <?= $form->field($model, 'ekg_prev[]')->fileInput(['multiple'=>true]) ?>
                                    </div>
                                    <div class="col-md-6">
                                        <p style="font-weight: 600;">Примеры качественных изображений ЭКГ</p>
                                        <?= Html::a(Html::img('/img/ekg/01.jpg', ['style'=>'height: 100px;']), '/img/ekg/01.jpg', ['class'=>'btn-magnific']) ?>
                                        <?= Html::a(Html::img('/img/ekg/02.jpg', ['style'=>'height: 100px;']), '/img/ekg/02.jpg', ['class'=>'btn-magnific']) ?>                                       
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <?= $form->field($model, 'ekg_date')->widget(MaskedInput::className(), [
                                            'mask'=>'99.99.9999',
                                            'options'=>[
                                                'class'=>'form-control'
                                            ]
                                        ])->error(false) ?>                                        
                                    </div> 
                                </div>
                                <div class="row">
                                    <div class="col-md-12">
                                        <?= $form->field($model, 'patient_sicks')->textarea([
                                            'class'=>'form-control',
                                            'placeholder'=>'Если жалоб / заболеваний нет, указать "Нет"',
                                            'rows'=>1,
                                            'style'=>'resize: vertical;'
                                        ])->error(false) ?>
                                        <?= $form->field($model, 'patient_drugs')->textarea([
                                            'class'=>'form-control',
                                            'placeholder'=>'Если лекарства не принимаете, указать "Нет"',
                                            'rows'=>1,
                                            'style'=>'resize: vertical;'
                                        ])->error(false) ?>
                                        <?= $form->field($model, 'patient_target')->textarea([
                                            'class'=>'form-control',
                                            'placeholder'=>'Если определенной цели расшифровки ЭКГ нет, указать "Нет"',
                                            'rows'=>1,
                                            'style'=>'resize: vertical;'
                                        ])->error(false) ?>
                                    </div>
                                </div>
                            </div>                            
                        </div>
                    </div>
                </div>
            </li>
            <li>
                <i class="fa fa-site-font bg-green">3</i>
                <div class="timeline-item">
                    <h2 class="timeline-header">Произведите оплату</h2>
                    <div class="timeline-body">
                        <div class="timeline-footer">
                            <p class="price" style="font-size: 20px; font-weight: 600;">
                                Стоимость стандартной расшифровки ЭКГ:&nbsp;
                                <?= Html::tag('span', Yii::$app->params['price']['cardio'] . ' руб.', ['style'=>'font-size: 22px; font-weight: 600;', 'class'=>'text-danger']) ?>
                            </p>
                            <?= Html::submitButton('Отправить данные', ['class'=>'btn btn-primary']) ?>
                            <p>*Нажимая на кнопку «Отправить данные» Вы соглашаетесь на обработку персональных данных.</p>
                        </div>
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