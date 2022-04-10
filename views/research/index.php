<?php
use kartik\select2\Select2;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = 'Просмотр результатов анализов';
$this->params['breadcrumbs'][] = $this->title;
$this->registerLinkTag(['rel'=>'canonical', 'href'=>Url::to(['/research'], 'https')]);

$years = [];
for ($i = date("Y"); $i >= 2010; $i--) { $years[$i] = $i; }
?>

<div class="row">
    <div class="col-md-12">
        <div class="box box-body box-primary">
            <div style="text-align: center;">
                <span style="font-size: 18px; font-weight: 600;">Где сдать анализы?</span><br><br><span>Сдать анализы вы можете <span style="text-decoration: underline"><?= Html::a('в любой поликлинике нашего медицинского объединения', 'http://0370.ru/polikliniki', ['target'=>'_blank']) ?>.</span></span>
            </div>        
			
            <div class="row">
                <div class="col-md-8 col-md-offset-2" style="background: url('/img/research-bg.png') no-repeat center; background-size: cover; border: 1px solid #ddd; border-radius: 4px; margin-top: 15px;">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2" style="padding: 30px;">
                            <?php $form = ActiveForm::begin(['action'=>['result']]) ?>

                            <?= $form->field($model, 'number')->textInput(['maxlength'=>true]) ?>

                            <?= $form->field($model, 'year')->widget(Select2::classname(), ['data'=>$years]) ?>

                            <div class="form-group">
                                <div class="row">
                                    <div class="col-md-6 margin-t-10">
                                        <?= Html::submitButton('Получить результаты!', ['class'=>'btn btn-primary']) ?>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end() ?>
                        </div>         
                    </div>
                </div>
            </div>
            <hr>
            <div style="text-align: center;">
                <span style="font-size: 18px; font-weight: 600;">Вы также можете узнать результаты теста на COVID-19, перейдя по <?= Html::a('ссылке', ['/covid/diagnosis']) ?>.</span>
            </div>
            <hr>
            <?php if ($slider) { 
                echo Html::beginTag('div', ['class'=>'row']);
                    echo Html::beginTag('div', ['class'=>'col-md-8 col-md-offset-2']);
                        echo $this->render('../site/_part/slider', ['model'=>$slider]);
                    echo Html::endTag('div');
                echo Html::endTag('div');
            } ?>            
        </div>
    </div>    
</div>